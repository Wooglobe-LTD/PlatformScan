<style>

    table.border {
        border: 1px solid darkgrey !important;
    }
    table.border>tr {
        line-height: 35px !important;
        border: 1px solid darkgrey !important;;
    }
    table.border>td {
        padding: 10px !important;
        border: 1px solid darkgrey !important;
    }

</style>
<table style="margin-top:40px;margin-left: 30px;margin-right: 30px;margin-bottom: 20px; ">
    <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td colspan="2" style="font-size: 16px;display: inline-block;vertical-align: top;">
            <b>WooGlobe Ltd.</b><br>
            16 Weir Road<br>
            Bexley, DA5 1BJ<br>
            United Kingdom<br>
            Email : Accounts@WooGlobe.com
        </td>
        <td colspan="2" align="right" style="text-align: right;display: inline-block;vertical-align: top;">
            <span style="font-size: 24px;"><b>REMITTANCE ADVICE</b></span><br>
            <span><b>Date : </b><?php echo $date;?></span><br>
            <span><b>REMITTANCE ADVICE REF : </b><?php echo $inv_id;?></span>
        </td>
    </tr>
    <tr>
        <td colspan="4">
            <br>
            <br>
            <br>
            <span><b>To</b></span><br>
            <?php echo $name;?><br>
            <?php echo $address;?><br>
            <?php echo $address2;?><br>
            <?php echo $country;?><br>
            Email : <?php echo $email;?>
        </td>
    </tr>
    <tr>
        <td colspan="4">
            <br>
            <br>
            <br>
            Payment Date : <?php echo $date;?><br>
            Payment Amount : <?php echo round($amount,2);?><br>
            Payment Currency : <?php echo $currency;?><br>
            Payment Method : <?php echo $method;?><br>
            PayPal Email : <?php echo $methodEmail;?><br>
            Transaction Id : <?php echo $transaction_id;?><br><br>
        </td>
    </tr>
</table>
<table class="border" border="1" cellspacing="1">
    <tr style="height: 20px;">
        <td style="text-align: center;width: 10%;"><b>SR#</b></td>
        <td style="text-align: center;width: 45%;"><b>DESCRIPTION</b></td>
        <td style="text-align: center;width: 15%;"><b>CURRENCY</b></td>
        <td style="text-align: center;width: 30%;"><b>AMOUNT</b></td>
    </tr>
    <?php foreach ($result->result() as $i=>$earning){ ?>
        <tr>
            <td style="text-align: center; width: 10%;"><?php echo ($i+1);?></td>
            <td style="text-align: left;width: 45%;"><?php echo $earning->unique_key;?> - Video Earnings</td>
            <td style="text-align: center;width: 15%;"><?php echo $earning->symbol;?></td>
            <td style="text-align: center;width: 30%;"><?php echo round($earning->payment,2);?></td>
        </tr>
    <?php } ?>
    <tr>
        <td colspan="3" style="text-align: right;"><b>SUBTOTAL</b></td>
        <td colspan="1" style="text-align: center;"><?php echo round($amount,2);?></td>
    </tr>
    <tr>
        <td colspan="3" style="text-align: right;"><b>WITHHOLDING TAX DEDUCTION</b></td>
        <td colspan="1" style="text-align: center;">0</td>
    </tr>
    <tr>
        <td colspan="3" style="text-align: right;"><b>ADDITIONAL TAX DEDUCTION</b></td>
        <td colspan="1" style="text-align: center;">0</td>
    </tr>
    <tr>
        <td colspan="3" style="text-align: right;"><b>TOTAL</b></td>
        <td colspan="1" style="text-align: center;"><?php echo $currency;?><?php echo round($amount,2) ;?></td>
    </tr>

</table>