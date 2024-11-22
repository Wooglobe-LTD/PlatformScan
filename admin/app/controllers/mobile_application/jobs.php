<?php

/**
 * Class to handle job execution
 */
class Jobs extends Onecore_Controller
{
	const STATUS_DONE = 'done';
	const STATUS_QUEUED = 'queued';
	const STATUS_RUNNING = 'running';

	protected $dbm;
	protected $cfg;

	/**
	 * Constructs the class
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model('MobileAppUser');
		$this->dbm = $this->MobileAppUser;
		if (php_sapi_name() !== 'cli')
		{
			die('Invalid context');
		}
	}

	/**
	 * Listener to process jobs
	 */
	public function listen()
	{
		$job = $this->db->query("
			SELECT id, name, payload
			FROM jobs
			WHERE status=?
			ORDER BY created_dt ASC
			LIMIT 1 FOR UPDATE", [self::STATUS_QUEUED])->result();

		if ($job !== [])
		{
			$job = $job[0];

			echo "\nProcessing job " . $job->id . "\n";

			if (!method_exists($this, $job->name))
			{
				throw new \RuntimeException('Job ' . $job->name . ' not found');
			}

			try
			{
				$start = microtime(true);
				$runtime = null;

				$this->db->query("UPDATE jobs SET status=? WHERE id=?", [self::STATUS_RUNNING, $job->id]);

				$jobHandler = [$this, $job->name];

				$payload = json_decode($job->payload, true);

				if (!is_array($payload))
				{
					throw new \InvalidArgumentException('Invalid payload format');
				}

				$payload = is_array($payload) ? $payload : [];

				$response = $jobHandler($payload);
				$runtime = microtime(true) - $start;
			}
			catch (\Exception $e)
			{
				$runtime = $runtime === null ?  microtime(true) - $start : $runtime;
				$response = $e->getMessage();
			}

			$this->db->query("UPDATE jobs SET status=?, run_time=?, response=? WHERE id=?", [
				self::STATUS_DONE,
				$runtime,
				json_encode($response),
				$job->id,
			]);

			echo "Job $job->id finished. \n";
		}
	}

	/**
	 * Send an email job
	 * @param  array  $payload  Payload of the job
	 * @return array ['sent' => 1|0]
	 */
	public function sendEmail(array $payload) : array
	{
		$this->load->library("utils");

		if (!isset($payload['subject']))
		{
			throw new \InvalidArgumentException('Invalid subject');
		}

		if (!isset($payload['message']))
		{
			throw new \InvalidArgumentException('Invalid message');
		}

		if (!isset($payload['to']))
		{
			throw new \InvalidArgumentException('Invalid to');
		}

		$sent = (int)$this->utils->sendEmail([
			'subject' => $payload['subject'],
			'type' => !isset($payload['type']) ? 'text' : $payload['type'],
			'message' => $payload['message'],
			'to' => $payload['to'],
		]);

		return [
			'sent' => "$sent",
		];
	}
}