<?php

set_time_limit(0);
ini_set('memory_limit', '5012M');
require_once APPPATH . "third_party/PHPExcel.php";

class MyExcelModel extends CI_Model {

    public function generateExcelFile($invoice_id, $type = "simple") {
        $pakistan_ids = array(282, 317);
        $invoice = $this->invoice->get_invoice($invoice_id);

switch ($invoice['master']->to_country_id) {
            /**
             * 282 - Pakistan
             * 317 - Pakistan Online
             */
            case 282: case 317:
                $obj1 = $this->generate_shipping_instructions_kch_sheet($invoice_id, $invoice);
                $obj3 = $this->generate_buyers_invoice_kch_sheet($invoice_id, $invoice);
//                        $obj3 = $this->generate_buyers_invoice_sheet($invoice_id, $invoice);
                $obj4 = $this->generate_attachement_sheet($invoice_id, $invoice);
                break;

            case 21:
                //UK
                $obj1 = $this->generate_shipping_instructions_uk_sheet($invoice_id, $invoice);
                $obj3 = $this->generate_buyers_invoice_uk_sheet($invoice_id, $invoice);
                $obj4 = $this->generate_attachement_uk_sheet($invoice_id, $invoice);
                break;

            case 396:
                //MONGOLIA
                $obj1 = $this->generate_shipping_instructions_mongolia_sheet($invoice_id, $invoice);
                $obj3 = $this->generate_buyers_invoice_mongolia_sheet($invoice_id, $invoice);
                $obj4 = $this->generate_attachement_sheet($invoice_id, $invoice);

                break;

            default:
                $obj1 = $this->generate_shipping_instructions_sheet($invoice_id, $invoice);
                $obj3 = $this->generate_buyers_invoice_sheet($invoice_id, $invoice);
                $obj4 = $this->generate_attachement_sheet($invoice_id, $invoice);
                break;
        }
        

//        $obj1 = $this->generate_shipping_instructions_sheet($invoice_id, $invoice);
//        $obj3 = $this->generate_buyers_invoice_sheet($invoice_id, $invoice);
//        $obj4 = $this->generate_attachement_sheet($invoice_id, $invoice);

        $obj2 = $this->generate_customs_invoice_sheet($invoice_id, $invoice, $type);
        $obj9 = $this->generate_customs_revised_invoice_sheet($invoice_id, $invoice, $type);

        $obj5 = $this->generate_packing_list_sheet($invoice_id, $invoice);
        $obj6 = $this->generate_car_details_sheet($invoice_id, $invoice);
        $obj7 = $this->generate_ship_details_sheet($invoice_id, $invoice);
        $obj8 = $this->generate_container_maps_sheet($invoice_id, $invoice);

        $inputFile = FCPATH . 'downloads/invoices/templates/inv_temp_blank.xlsx';

        $reader = new PHPExcel_Reader_Excel2007();
        $objPHPExcel = $reader->load($inputFile);

        $objPHPExcel->setActiveSheetIndex(0);

        $shippingSheets = $obj1->getAllSheets();

        foreach ($shippingSheets as $shippingSheet) {
            $objPHPExcel->addExternalSheet($shippingSheet);
        }

        $obj2->setActiveSheetIndex(0);
        $sheet2 = $obj2->getActiveSheet();

        $objPHPExcel->addExternalSheet($sheet2);

        $obj9->setActiveSheetIndex(0);
        $sheet2 = $obj9->getActiveSheet();

        $objPHPExcel->addExternalSheet($sheet2);
        
        $buyersSheets = $obj3->getAllSheets();

        foreach ($buyersSheets as $buyersSheet) {
            $objPHPExcel->addExternalSheet($buyersSheet);
        }

//        $obj4->setActiveSheetIndex(0);
//        $sheet4 = $obj4->getActiveSheet();

        $attachementSheets = $obj4->getAllSheets();

        foreach ($attachementSheets as $attachementSheet) {
            $objPHPExcel->addExternalSheet($attachementSheet);
        }

        $obj5->setActiveSheetIndex(0);
        $sheet5 = $obj5->getActiveSheet();

        $obj6->setActiveSheetIndex(0);
        $sheet6 = $obj6->getActiveSheet();

        $obj7->setActiveSheetIndex(0);
        $sheet7 = $obj7->getActiveSheet();

//        $objPHPExcel->addExternalSheet($sheet4);
        $objPHPExcel->addExternalSheet($sheet5);
        $objPHPExcel->addExternalSheet($sheet6);
        $objPHPExcel->addExternalSheet($sheet7);

        $mapSheets = $obj8->getAllSheets();

        foreach ($mapSheets as $mapSheet) {
            $objPHPExcel->addExternalSheet($mapSheet);
        }

        $objPHPExcel->removeSheetByIndex(0);

        $objPHPExcel->setActiveSheetIndex(0);

//        display_admin_debug("check", "variable");
        return $objPHPExcel;
    }

    public function generate_customs_invoice_sheet($invoice_id, $invoice, $type = "simple", $html = FALSE) {

//        display_admin_debug($invoice, "array");
        $result_data = array();
        $result_data['bl_no'] = $invoice['master']->bl_no;
        $result_data['booking_no'] = $invoice['master']->booking_no;
        $result_data['invoice_no'] = $invoice['master']->invoice_no;

        $invoice_year_id = $invoice['invoice_year_details']->invoice_year_id;


        if (stristr($invoice['master']->to_port_name, "Durban")) {
            $invoice['master']->to_port_name = "DURBAN";
        }

        $from_address_B8 = "";
        if ($invoice['master']->from_city_id == 255) {
            $from_address_B8 = "HAKATA, JAPAN";
        } else if ($invoice['master']->from_city_id == 308) {
            $from_address_B8 = "YOKOHAMA VIA TOMAKOMAI, JAPAN";
        } else {
            //20 - Kenya
            //212 - Namibia
            //10 - Tanzania
            //279 - Uganda
            if ($invoice['master']->to_country_id == 20 || $invoice['master']->to_country_id == 212 || $invoice['master']->to_country_id == 10 || $invoice['master']->to_country_id == 279) {
                $invoice['master']->place_of_receipt = str_ireplace(", CY", "", $invoice['master']->place_of_receipt);
                $from_address_B8 .= $invoice['master']->place_of_receipt . ", " . $invoice['master']->from_country_name;
            } else {
                $from_address_B8 .= $invoice['master']->from_port_name . ", " . $invoice['master']->from_country_name;
            }
        }

        $inputFile = FCPATH . 'downloads/invoices/templates/inv_temp_customs_invoice.xlsx';

        $reader = new PHPExcel_Reader_Excel2007();
        $objPHPExcel = $reader->load($inputFile);
        $objPHPExcel->setActiveSheetIndex(0);
        $sheet2 = $objPHPExcel->getActiveSheet();

        /**
         * * ******************************************** */
        $all_containers_total_units_count = 0;
        $all_containers_total_pcs_count = 0;
        $all_containers_total_amount = 0;
        $all_containers_total_g_weight = 0;
        $all_containers_no_commercial_total_amount = 0;

        $total_containers = 0;
        $total_cars = 0;
        $invoice_total_amount = 0;
        $invoice_total_weight = 0;
        $invoice_total_units_count = 0;
        $invoice_total_pcs_count = 0;
        $invoice_no_commercial_total_amount = 0;
        $invoice_str_quantity = "";
        $str_invoice_no_commercial = "";

        $row = 28;
        foreach ($invoice['containers'] as $container) {
            $container_units_count = 0;
            $container_pcs_count = 0;
            $container_g_weight = 0;
            $str_quanity = "";

            $total_containers++;

            foreach ($container['details']['cars'] as $car) {

                $total_cars++;
                $container_units_count++;
                $container_g_weight += $car->weight;
                $all_containers_total_amount += $car->amount;
            }
            foreach ($container['details']['others'] as $other) {
                $other_amount = $other->unit_price * $other->quantity;
                if ($other->commercial) {
                    $all_containers_total_amount += $other_amount;
                } else {
                    $all_containers_no_commercial_total_amount += $other_amount;
                }
                if ($other->quantity_unit == "UNITS") {
                    $container_units_count += $other->quantity;
                } else if ($other->quantity_unit == "PCS") {
                    $container_pcs_count += $other->quantity;
                }
                $container_g_weight += $other->weight;
            }
            $all_containers_total_units_count += $container_units_count;
            $all_containers_total_pcs_count += $container_pcs_count;


            if ($container_units_count && $container_pcs_count) {
                $str_quanity = "$container_units_count UNITS & $container_pcs_count PCS";
            } else if ($container_units_count && !$container_pcs_count) {
                $str_quanity = "$container_units_count UNITS";
            } else if (!$container_units_count && $container_pcs_count) {
                $str_quanity = "$container_pcs_count PCS";
            }

            $all_containers_total_g_weight += $container_g_weight;

            $row++;
        }
        $row += 2;

        $str_quanity_total = "";
        if ($all_containers_total_units_count && $all_containers_total_pcs_count) {
            $str_quanity_total = "$all_containers_total_units_count UNITS & $all_containers_total_pcs_count PCS";
        } else if ($all_containers_total_units_count && !$all_containers_total_pcs_count) {
            $str_quanity_total = "$all_containers_total_units_count UNITS";
        } else if (!$all_containers_total_units_count && $all_containers_total_pcs_count) {
            $str_quanity_total = "$all_containers_total_pcs_count PCS";
        }

        $invoice_total_amount += $all_containers_total_amount;
        $invoice_total_weight += $all_containers_total_g_weight;

        $invoice_total_units_count += $all_containers_total_units_count;
        $invoice_total_pcs_count += $all_containers_total_pcs_count;
        $invoice_no_commercial_total_amount += $all_containers_no_commercial_total_amount;


        $forwarding_charges_text = "";
        foreach ($invoice['others'] as $inv_other) {
            $inv_amount = 0;
            //merged
            if ($type == "merged" && stristr($inv_other->description, "inspection")) {
                $inv_amount = $inv_other->amount;
            } else {
                $inv_amount = $inv_other->quantity * $inv_other->unit_price;
            }
            if ($inv_other->commercial) {
                if (!(stristr($inv_other->description, "freight") && $invoice['master']->to_country_id == 282)) {
                    $invoice_total_amount += $inv_amount;
                }
            } else {
                $invoice_no_commercial_total_amount += $inv_amount;
            }

            if (!stristr($inv_other->description, "forwarding") && !stristr($inv_other->description, "inspection") && !stristr($inv_other->description, "freight")) {
                $invoice_total_weight += $inv_other->weight;
                if ($inv_other->quantity_unit == "UNITS") {
                    $invoice_total_units_count += $inv_other->quantity;
                } else if ($inv_other->quantity_unit == "PCS") {
                    $invoice_total_pcs_count += $inv_other->quantity;
                }
            } else if (stristr($inv_other->description, "forwarding")) {
                $fwd_charges = number_format($inv_other->unit_price);

                switch ($invoice_year_id) {
                    case 1:
                        $forwarding_charges_text = "(JPY$fwd_charges PER UNIT)";
                        break;
                    case 2:
                        $forwarding_charges_text = "(JPY$fwd_charges PER CONTAINER)";
                        break;
                }
            } else if (stristr($inv_other->description, "freight")) {
                if ($invoice['master']->to_country_id != 310) {
                    $sheet2->setCellValue('C21', 'FREIGHT CHARGES ($' . $inv_other->usd_unit_price . ' PER VAN)');
                    $sheet2->setCellValue('E21', $total_containers . ' VANS');
                    $total_freight = $inv_other->usd_unit_price * $total_containers;
                    $sheet2->setCellValue('G21', 'US$' . $total_freight);
                }
            }
        }

        if ($invoice_total_units_count && $invoice_total_pcs_count) {
            $invoice_str_quantity = "$invoice_total_units_count UNITS & $invoice_total_pcs_count PCS";
        } else if ($invoice_total_units_count && !$invoice_total_pcs_count) {
            $invoice_str_quantity = "$invoice_total_units_count UNITS";
        } else if (!$invoice_total_units_count && $invoice_total_pcs_count) {
            $invoice_str_quantity = "$invoice_total_pcs_count PCS";
        }
        if ($invoice_no_commercial_total_amount) {
            $str_invoice_no_commercial = "(NO COMMERICAL VALUE ¥$invoice_no_commercial_total_amount)";
        }

        /**
         * *************************************************************** */
        $merge_cells_range = array();

        $sheet2->setCellValue('E4', "DATE  " . date_to_invoice($invoice['master']->invoice_date));

        $sheet2->setCellValue('E5', "NO. " . $invoice['invoice_no_original']);
        $sheet2->setCellValue('B6', $invoice['master']->invoice_type_name);

//        $sheet2->setCellValue('C7', "\"" . $invoice['master']->vessel_name . "\"  VOY. " . $invoice['master']->voyage_no);
//        $sheet2->setCellValue('G7', "on/ about " . date_to_invoice($invoice['master']->shipment_date));
        $sheet2->setCellValue('B7', "\"" . $invoice['master']->vessel_name . "\"  VOY. " . $invoice['master']->voyage_no);
        $sheet2->setCellValue('E7', "on/ about " . date_to_invoice($invoice['master']->shipment_date));
        $sheet2->setCellValue('B8', $from_address_B8);

        if ($invoice['master']->to_country_id == 359) {
            $f8 = "SWAZILAND VIA DURBAN";
        } else if ($invoice['master']->to_country_id == 353) {
            $f8 = "LESOTHO VIA DURBAN";
        } else if ($invoice['master']->to_country_id == 396) {
            $f8 = "MONGOLIA VIA XINGANG, CHINA";
        } else {
            if ($invoice['master']->to_country_id == 317) {
                $invoice['master']->to_country_name = "PAKISTAN";
            } else if ($invoice['master']->to_country_id == 394) {
                $invoice['master']->to_country_name = "MOZAMBIQUE";
            }
            $f8 = $invoice['master']->to_port_name . ", " . $invoice['master']->to_country_name;
        }
        if (stristr($f8, "MESSINA ONLINE")) {
            $f8 = "TO MESSINA VIA DURBAN";
        }
        $sheet2->setCellValue('F8', $f8);

//        $invoice['master']->consignee_office_address = str_ireplace("\m", "\n", $invoice['master']->consignee_office_address);
        $customs_consignee_office_name = $invoice['consignees']['customs_consignee']->office_name;
        $customs_consignee_office_address = str_replace("\m", "\n", $invoice['consignees']['customs_consignee']->office_address);

        //214 - Mozambique
        if ($invoice['master']->to_country_id == 214) {
            $str_pos = strpos($customs_consignee_office_address, "E-mail:");
            $customs_consignee_office_address = substr($customs_consignee_office_address, 0, $str_pos);
        }

        $sheet2->setCellValue('B9', $customs_consignee_office_name);
        $sheet2->setCellValue('B10', $customs_consignee_office_address);
        $sheet2->setCellValue('B12', $invoice['master']->payment_type);
        $sheet2->setCellValue('D12', "B/L NO " . $invoice['master']->bl_no);
        $sheet2->setCellValue('E12', "BOOKING NO. " . $invoice['master']->booking_no);
        $sheet2->setCellValue('A13', "INV. NO. " . $invoice['master']->invoice_no);
        $sheet2->setCellValue('A15', $invoice['master']->marks);
        $sheet2->setCellValue('C15', $invoice['master']->invoice_type_name);
        $sheet2->setCellValue('F15', $invoice['master']->info);
        $sheet2->setCellValue('A22', number_format($invoice_total_weight) . " KGS");
        $sheet2->setCellValue('C22', "40' x $total_containers CONTAINERS");
        $sheet2->setCellValue('E22', $invoice_str_quantity);
        $sheet2->setCellValue('G22', "¥" . number_format($invoice_total_amount));

        $sheet2->setCellValue('E23', $str_invoice_no_commercial);
        $sheet2->getStyle('E23')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

        $no_of_lines = array();
        $line_no = 16;
        $row = 17;
        $cars = 0;

        foreach ($invoice['containers'] as $container) {

            $sheet2->insertNewRowBefore($row, 1);
            $sheet2->setCellValue('A' . $row, $container['container_no']);
            $sheet2->getStyle('A' . $row)->getAlignment()->setWrapText(FALSE);
            $sheet2->getStyle('A' . $row)->getAlignment()->setShrinkToFit(TRUE);
            $row++;
            $line_no++;

            $no_of_lines[] = array(
                'key' => "containers_" . $container['container_no'],
                'value' => $line_no);
            foreach ($container['details']['cars'] as $car) {
                $cars++;
                $sheet2->insertNewRowBefore($row, 1);
                $line_no++;
                $start = $row;
                $sheet2->setCellValue('A' . $row, number_format(round($car->weight)) . " KGS");
                $sheet2->getStyle('A' . $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $sheet2->setCellValue('B' . $row, $car->item_no);
                $sheet2->setCellValue('C' . $row, "USED $car->maker_name");
                $sheet2->getStyle('C' . $row)->getAlignment()->setWrapText(FALSE);
                $sheet2->getStyle('C' . $row)->getAlignment()->setShrinkToFit(TRUE);

                $grade_model = $car->model_name;
                if ($car->imported) {
                    $grade_model .= " $car->grade_name";
                }
                $sheet2->setCellValue('D' . $row, "$grade_model");
                $sheet2->getStyle('D' . $row)->getAlignment()->setWrapText(FALSE);
                $sheet2->getStyle('D' . $row)->getAlignment()->setShrinkToFit(TRUE);

                $sheet2->setCellValue('E' . $row, "1 UNIT");
                $sheet2->getStyle('E' . $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
//                $sheet2->setCellValue('F' . $row, "¥" . number_format($car->amount));
                $sheet2->setCellValue('F' . $row, $car->amount);
                $sheet2->getStyle('F' . $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
//                $sheet2->setCellValue('G' . $row, "¥" . number_format($car->amount));
                $sheet2->setCellValue('G' . $row, $car->amount);
                $sheet2->getStyle('G' . $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $row++;

                $sheet2->insertNewRowBefore($row, 1);
                $line_no++;
                $sheet2->setCellValue('C' . $row, "CHASSIS NO.");
                $sheet2->setCellValue('D' . $row, $car->chassis_no);
                $row++;

                $sheet2->insertNewRowBefore($row, 1);
                $line_no++;
                $sheet2->setCellValue('C' . $row, "ENGINE NO.");
                $sheet2->setCellValue('D' . $row, "$car->engine_no (" . $car->fuel_name . "/$car->engine_size CC)");
                $sheet2->getStyle('D' . $row)->getAlignment()->setWrapText(FALSE);
                $sheet2->getStyle('D' . $row)->getAlignment()->setShrinkToFit(TRUE);
                $row++;

                //Namibia - 212
                // kenya - 20
                $o_e_c = array(20, 212);
                if (in_array($car->to_country_id, $o_e_c)) {
                    $sheet2->insertNewRowBefore($row, 1);
                    $line_no++;
                    $sheet2->setCellValue('C' . $row, "ENGINE NO.");
                    $sheet2->setCellValue('D' . $row, $car->engine_code);
                    $row++;
                }

                //imported and uae
                if ($car->imported && $car->to_country_id == 17) {
                    $sheet2->insertNewRowBefore($row, 1);
                    $line_no++;
                    $sheet2->setCellValue('C' . $row, "STEERING");
                    $sheet2->setCellValue('D' . $row, $car->steering_name);
                    $row++;

                    $sheet2->insertNewRowBefore($row, 1);
                    $line_no++;
                    $sheet2->setCellValue('C' . $row, "COUNTRY");
                    $sheet2->setCellValue('D' . $row, $car->maker_country);
                    $row++;

                    $sheet2->insertNewRowBefore($row, 1);
                    $line_no++;
                    $sheet2->setCellValue('C' . $row, "TYPE");
                    $sheet2->setCellValue('D' . $row, $car->body_type_name);
                    $row++;
                }

                $sheet2->insertNewRowBefore($row, 1);
                $line_no++;

                $reg_year_month_label = "REG YEAR";
                $reg_year_month_value = $car->registration_year;
                //Kenya
                if ($car->to_country_id == 20) {
                    $reg_year_month_label .= "/MONTH";
                    $reg_year_month_value .= "/" . $car->registration_month;
                }
                $sheet2->setCellValue('C' . $row, $reg_year_month_label);
                $sheet2->setCellValue('D' . $row, $reg_year_month_value);
                $row++;

                //Tanzania
                if ($car->to_country_id == 10) {
                    $sheet2->insertNewRowBefore($row, 1);
                    $line_no++;
                    $sheet2->setCellValue('C' . $row, "MFG YEAR:");
                    $sheet2->setCellValue('D' . $row, "$car->manufacture_year");
                    $row++;
                }

                $sheet2->insertNewRowBefore($row, 1);
                $line_no++;
                $sheet2->setCellValue('C' . $row, "COLOR");
                $sheet2->setCellValue('D' . $row, $car->color_name);

                $merge_cells_range[] = 'A' . $start . ':A' . $row;
                $merge_cells_range[] = 'B' . $start . ':B' . $row;
                $merge_cells_range[] = 'E' . $start . ':E' . $row;
                $merge_cells_range[] = 'F' . $start . ':F' . $row;
                $merge_cells_range[] = 'G' . $start . ':G' . $row;

//                if ($cars == 5 || ($cars > 5 && (($cars - 5) % 7 == 0))) {
//                    $pageBreaks[] = 'A' . $row;
//                }
                $row++;
                $no_of_lines[] = array(
                    'key' => "car_" . $car->car_id,
                    'value' => $line_no);
            }

            $i = 1;
            foreach ($container['details']['others'] as $other) {

                $sheet2->insertNewRowBefore($row, 1);
                $line_no++;

                $sheet2->setCellValue('A' . $row, number_format(round($other->weight)) . " KGS");
                $sheet2->getStyle('A' . $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

                $sheet2->setCellValue('C' . $row, "$other->description");

                $merge_cells_range[] = 'C' . $row . ':D' . $row;

                $sheet2->setCellValue('E' . $row, "$other->quantity $other->quantity_unit");
//                $sheet2->setCellValue('F' . $row, "¥" . number_format($other->unit_price));
                $sheet2->setCellValue('F' . $row, $other->unit_price);
                $other_total = $other->quantity * $other->unit_price;
//                $sheet2->setCellValue('G' . $row, "¥" . number_format($other_total));
                $sheet2->setCellValue('G' . $row, $other_total);
                $row++;

                if (!$other->commercial) {

                    $sheet2->insertNewRowBefore($row, 1);
                    $line_no++;
                    $sheet2->setCellValue('E' . $row, "(NO COMMERICAL VALUE)");
                    $sheet2->getStyle('E' . $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    $merge_cells_range[] = 'E' . $row . ':G' . $row;

                    $row++;
                }
                $no_of_lines[] = array(
                    'key' => "other_" . $i,
                    'value' => $line_no);
                $i++;
            }
//            $line_no++;
        }

        $sheet2->insertNewRowBefore($row, 1);
        $line_no++;
        $row++;
        $i = 1;

        foreach ($invoice['others'] as $inv_other) {

            if ($inv_other->amount > 0) {
                if (!stristr($inv_other->description, "freight")) {
                    $sheet2->insertNewRowBefore($row, 1);
                    $line_no++;
                    $inv_other->weight = round($inv_other->weight);

                    if ($inv_other->weight) {
                        $sheet2->setCellValue('A' . $row, number_format(round($inv_other->weight)) . " KGS");
                        $sheet2->getStyle('A' . $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                    }

                    $sheet2->setCellValue('C' . $row, "$inv_other->description");

                    $merge_cells_range[] = 'C' . $row . ':D' . $row;

                    if (!stristr($inv_other->description, "forwarding")) {
                        if (!stristr($inv_other->description, "freight")) {
                            $sheet2->setCellValue('E' . $row, "$inv_other->quantity $inv_other->quantity_unit");
//                    $sheet2->setCellValue('F' . $row, "¥" . number_format($inv_other->unit_price));
                            if (($type == "merged" && stristr($inv_other->description, "inspection"))) {
//                        $sheet2->setCellValue('F' . $row, $inv_other->unit_price);
                                $inv_other->unit_price = $inv_other->amount / $inv_other->quantity;
                            }
                            $sheet2->setCellValue('F' . $row, $inv_other->unit_price);
                        }
                    }

                    if (!stristr($inv_other->description, "freight")) {

                        if ($type == "merged" && stristr($inv_other->description, "inspection")) {
                            $inv_other_total = $inv_other->amount;
                        } else {
                            $inv_other_total = $inv_other->quantity * $inv_other->unit_price;
                        }
//                $sheet2->setCellValue('G' . $row, "¥" . number_format($inv_other_total));
                        $sheet2->setCellValue('G' . $row, $inv_other_total);
                    }
                    $row++;

                    if (stristr($inv_other->description, "forwarding")) {
                        $sheet2->insertNewRowBefore($row, 1);
                        $line_no++;
                        $sheet2->setCellValue('C' . $row, $forwarding_charges_text);
                        $merge_cells_range[] = 'C' . $row . ':D' . $row;
                        $row++;
                    }

                    if (!$inv_other->commercial) {

                        if (!stristr($inv_other->description, "freight")) {
                            $sheet2->insertNewRowBefore($row, 1);
                            $line_no++;
                            $sheet2->setCellValue('E' . $row, "(NO COMMERICAL VALUE)");
                            $merge_cells_range[] = 'E' . $row . ':G' . $row;
                            $row++;
                        }
                    }
                    if (stristr($inv_other->description, "inspection")) {
                        $sheet2->insertNewRowBefore($row, 1);
                        $line_no++;
                        $row++;
                    }

                    $no_of_lines[] = array(
                        'key' => "inv_other_" . $i,
                        'value' => ($line_no - 1));
                }
            }
        }

        $line_no += 13;
        $no_of_lines[] = array(
            'key' => "inv_other_" . $i,
            'value' => ($line_no - 1));
        $invoice_sheet_last_row = $row;

        $pageBreaks = array();
        $page_lines = 51;
        foreach ($no_of_lines as $index => $node) {

            if (isset($no_of_lines[$index + 1])) {

                if ($node['value'] <= $page_lines && ($no_of_lines[$index + 1]['value'] > $page_lines)) {
                    if (strstr($node['key'], "car")) {
                        $pageBreaks[] = "A" . ($node['value']);
                    } else if (strstr($node['key'], "containers")) {
                        if ($no_of_lines[$index + 1]['value'] > $page_lines) {
                            $pageBreaks[] = "A" . ($no_of_lines[$index - 1]['value']);
                        } else {
                            $pageBreaks[] = "A" . ($node['value']);
                        }
                    } else {
                        $pageBreaks[] = "A" . ($node['value'] - 1);
                    }
                    $page_lines += 51;
                }
            }
        }

        foreach ($merge_cells_range as $cell_range) {
            $sheet2->mergeCells($cell_range);
        }

        $sheet2->getPageSetup()->setRowsToRepeatAtTop(array(13, 14));

        /**
         * ***************************************************************** */
        $sheet2->getPageSetup()->setFitToWidth(1);
        $sheet2->getPageSetup()->setFitToHeight(0);
        $sheet2->getPageSetup()->setHorizontalCentered(1);
        foreach ($pageBreaks as $pb) {
            $sheet2->setBreak($pb, PHPExcel_Worksheet::BREAK_ROW);
        }

        return $objPHPExcel;
    }

    public function generate_customs_revised_invoice_sheet($invoice_id, $invoice, $type = "simple", $html = FALSE) {

//        display_admin_debug($invoice, "array");
        $result_data = array();
        $result_data['bl_no'] = $invoice['master']->bl_no;
        $result_data['booking_no'] = $invoice['master']->booking_no;
        $result_data['invoice_no'] = $invoice['master']->invoice_no;

        $invoice_year_id = $invoice['invoice_year_details']->invoice_year_id;


        if (stristr($invoice['master']->to_port_name, "Durban")) {
            $invoice['master']->to_port_name = "DURBAN";
        }

        $from_address_B8 = "";
        if ($invoice['master']->from_city_id == 255) {
            $from_address_B8 = "HAKATA, JAPAN";
        } else if ($invoice['master']->from_city_id == 308) {
            $from_address_B8 = "YOKOHAMA VIA TOMAKOMAI, JAPAN";
        } else {
            //20 - Kenya
            //212 - Namibia
            //10 - Tanzania
            //279 - Uganda
            if ($invoice['master']->to_country_id == 20 || $invoice['master']->to_country_id == 212 || $invoice['master']->to_country_id == 10 || $invoice['master']->to_country_id == 279) {
                $invoice['master']->place_of_receipt = str_ireplace(", CY", "", $invoice['master']->place_of_receipt);
                $from_address_B8 .= $invoice['master']->place_of_receipt . ", " . $invoice['master']->from_country_name;
            } else {
                $from_address_B8 .= $invoice['master']->from_port_name . ", " . $invoice['master']->from_country_name;
            }
        }

        $inputFile = FCPATH . 'downloads/invoices/templates/inv_temp_customs_invoice.xlsx';

        $reader = new PHPExcel_Reader_Excel2007();
        $objPHPExcel = $reader->load($inputFile);
        $objPHPExcel->setActiveSheetIndex(0);
        $sheet2 = $objPHPExcel->getActiveSheet();
        $sheet2->setTitle("customs_invoice_customer");

        /**
         * * ******************************************** */
        $all_containers_total_units_count = 0;
        $all_containers_total_pcs_count = 0;
        $all_containers_total_amount = 0;
        $all_containers_total_g_weight = 0;
        $all_containers_no_commercial_total_amount = 0;

        $total_containers = 0;
        $total_cars = 0;
        $invoice_total_amount = 0;
        $invoice_total_weight = 0;
        $invoice_total_units_count = 0;
        $invoice_total_pcs_count = 0;
        $invoice_no_commercial_total_amount = 0;
        $invoice_str_quantity = "";
        $str_invoice_no_commercial = "";

        $row = 28;
        foreach ($invoice['containers'] as $container) {
            $container_units_count = 0;
            $container_pcs_count = 0;
            $container_g_weight = 0;
            $str_quanity = "";

            $total_containers++;

            foreach ($container['details']['cars'] as $car) {

                $total_cars++;
                $container_units_count++;
                $container_g_weight += $car->weight;
                $all_containers_total_amount += $car->amount_custom;
            }
            foreach ($container['details']['others'] as $other) {
                $other_amount = $other->unit_price * $other->quantity;
                if ($other->commercial) {
                    $all_containers_total_amount += $other_amount;
                } else {
                    $all_containers_no_commercial_total_amount += $other_amount;
                }
                if ($other->quantity_unit == "UNITS") {
                    $container_units_count += $other->quantity;
                } else if ($other->quantity_unit == "PCS") {
                    $container_pcs_count += $other->quantity;
                }
                $container_g_weight += $other->weight;
            }
            $all_containers_total_units_count += $container_units_count;
            $all_containers_total_pcs_count += $container_pcs_count;


            if ($container_units_count && $container_pcs_count) {
                $str_quanity = "$container_units_count UNITS & $container_pcs_count PCS";
            } else if ($container_units_count && !$container_pcs_count) {
                $str_quanity = "$container_units_count UNITS";
            } else if (!$container_units_count && $container_pcs_count) {
                $str_quanity = "$container_pcs_count PCS";
            }

            $all_containers_total_g_weight += $container_g_weight;

            $row++;
        }
        $row += 2;

        $str_quanity_total = "";
        if ($all_containers_total_units_count && $all_containers_total_pcs_count) {
            $str_quanity_total = "$all_containers_total_units_count UNITS & $all_containers_total_pcs_count PCS";
        } else if ($all_containers_total_units_count && !$all_containers_total_pcs_count) {
            $str_quanity_total = "$all_containers_total_units_count UNITS";
        } else if (!$all_containers_total_units_count && $all_containers_total_pcs_count) {
            $str_quanity_total = "$all_containers_total_pcs_count PCS";
        }

        $invoice_total_amount += $all_containers_total_amount;
        $invoice_total_weight += $all_containers_total_g_weight;

        $invoice_total_units_count += $all_containers_total_units_count;
        $invoice_total_pcs_count += $all_containers_total_pcs_count;
        $invoice_no_commercial_total_amount += $all_containers_no_commercial_total_amount;


        $forwarding_charges_text = "";
        foreach ($invoice['others'] as $inv_other) {
            $inv_amount = 0;
            //merged
            if ($type == "merged" && stristr($inv_other->description, "inspection")) {
                $inv_amount = $inv_other->amount;
            } else {
                $inv_amount = $inv_other->quantity * $inv_other->unit_price;
            }
            if ($inv_other->commercial) {
                if (!(stristr($inv_other->description, "freight") && $invoice['master']->to_country_id == 282)) {
                    $invoice_total_amount += $inv_amount;
                }
            } else {
                $invoice_no_commercial_total_amount += $inv_amount;
            }

            if (!stristr($inv_other->description, "forwarding") && !stristr($inv_other->description, "inspection") && !stristr($inv_other->description, "freight")) {
                $invoice_total_weight += $inv_other->weight;
                if ($inv_other->quantity_unit == "UNITS") {
                    $invoice_total_units_count += $inv_other->quantity;
                } else if ($inv_other->quantity_unit == "PCS") {
                    $invoice_total_pcs_count += $inv_other->quantity;
                }
            } else if (stristr($inv_other->description, "forwarding")) {
                $fwd_charges = number_format($inv_other->unit_price);

                switch ($invoice_year_id) {
                    case 1:
                        $forwarding_charges_text = "(JPY$fwd_charges PER UNIT)";
                        break;
                    case 2:
                        $forwarding_charges_text = "(JPY$fwd_charges PER CONTAINER)";
                        break;
                }
            } else if (stristr($inv_other->description, "freight")) {
                if ($invoice['master']->to_country_id != 310) {
                    $sheet2->setCellValue('C21', 'FREIGHT CHARGES ($' . $inv_other->usd_unit_price . ' PER VAN)');
                    $sheet2->setCellValue('E21', $total_containers . ' VANS');
                    $total_freight = $inv_other->usd_unit_price * $total_containers;
                    $sheet2->setCellValue('G21', 'US$' . $total_freight);
                }
            }
        }

        if ($invoice_total_units_count && $invoice_total_pcs_count) {
            $invoice_str_quantity = "$invoice_total_units_count UNITS & $invoice_total_pcs_count PCS";
        } else if ($invoice_total_units_count && !$invoice_total_pcs_count) {
            $invoice_str_quantity = "$invoice_total_units_count UNITS";
        } else if (!$invoice_total_units_count && $invoice_total_pcs_count) {
            $invoice_str_quantity = "$invoice_total_pcs_count PCS";
        }
        if ($invoice_no_commercial_total_amount) {
            $str_invoice_no_commercial = "(NO COMMERICAL VALUE ¥$invoice_no_commercial_total_amount)";
        }

        /**
         * *************************************************************** */
        $merge_cells_range = array();

        $sheet2->setCellValue('E4', "DATE  " . date_to_invoice($invoice['master']->invoice_date));

        $sheet2->setCellValue('E5', "NO. " . $invoice['invoice_no_original']);
        $sheet2->setCellValue('B6', $invoice['master']->invoice_type_name);

//        $sheet2->setCellValue('C7', "\"" . $invoice['master']->vessel_name . "\"  VOY. " . $invoice['master']->voyage_no);
//        $sheet2->setCellValue('G7', "on/ about " . date_to_invoice($invoice['master']->shipment_date));
        $sheet2->setCellValue('B7', "\"" . $invoice['master']->vessel_name . "\"  VOY. " . $invoice['master']->voyage_no);
        $sheet2->setCellValue('E7', "on/ about " . date_to_invoice($invoice['master']->shipment_date));
        $sheet2->setCellValue('B8', $from_address_B8);

        if ($invoice['master']->to_country_id == 359) {
            $f8 = "SWAZILAND VIA DURBAN";
        } else if ($invoice['master']->to_country_id == 353) {
            $f8 = "LESOTHO VIA DURBAN";
        } else if ($invoice['master']->to_country_id == 396) {
            $f8 = "MONGOLIA VIA XINGANG, CHINA";
        } else {
            if ($invoice['master']->to_country_id == 317) {
                $invoice['master']->to_country_name = "PAKISTAN";
            } else if ($invoice['master']->to_country_id == 394) {
                $invoice['master']->to_country_name = "MOZAMBIQUE";
            }
            $f8 = $invoice['master']->to_port_name . ", " . $invoice['master']->to_country_name;
        }
        if (stristr($f8, "MESSINA ONLINE")) {
            $f8 = "TO MESSINA VIA DURBAN";
        }
        $sheet2->setCellValue('F8', $f8);

//        $invoice['master']->consignee_office_address = str_ireplace("\m", "\n", $invoice['master']->consignee_office_address);
        $customs_consignee_office_name = $invoice['consignees']['customs_consignee']->office_name;
        $customs_consignee_office_address = str_replace("\m", "\n", $invoice['consignees']['customs_consignee']->office_address);

        //214 - Mozambique
        if ($invoice['master']->to_country_id == 214) {
            $str_pos = strpos($customs_consignee_office_address, "E-mail:");
            $customs_consignee_office_address = substr($customs_consignee_office_address, 0, $str_pos);
        }

        $sheet2->setCellValue('B9', $customs_consignee_office_name);
        $sheet2->setCellValue('B10', $customs_consignee_office_address);
        $sheet2->setCellValue('B12', $invoice['master']->payment_type);
        $sheet2->setCellValue('D12', "B/L NO " . $invoice['master']->bl_no);
        $sheet2->setCellValue('E12', "BOOKING NO. " . $invoice['master']->booking_no);
        $sheet2->setCellValue('A13', "INV. NO. " . $invoice['master']->invoice_no);
        $sheet2->setCellValue('A15', $invoice['master']->marks);
        $sheet2->setCellValue('C15', $invoice['master']->invoice_type_name);
        $sheet2->setCellValue('F15', $invoice['master']->info);
        $sheet2->setCellValue('A22', number_format($invoice_total_weight) . " KGS");
        $sheet2->setCellValue('C22', "40' x $total_containers CONTAINERS");
        $sheet2->setCellValue('E22', $invoice_str_quantity);
        $sheet2->setCellValue('G22', "¥" . number_format($invoice_total_amount));

        $sheet2->setCellValue('E23', $str_invoice_no_commercial);
        $sheet2->getStyle('E23')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

        $no_of_lines = array();
        $line_no = 16;
        $row = 17;
        $cars = 0;

        foreach ($invoice['containers'] as $container) {

            $sheet2->insertNewRowBefore($row, 1);
            $sheet2->setCellValue('A' . $row, $container['container_no']);
            $sheet2->getStyle('A' . $row)->getAlignment()->setWrapText(FALSE);
            $sheet2->getStyle('A' . $row)->getAlignment()->setShrinkToFit(TRUE);
            $row++;
            $line_no++;

            $no_of_lines[] = array(
                'key' => "containers_" . $container['container_no'],
                'value' => $line_no);
            foreach ($container['details']['cars'] as $car) {
                $cars++;
                $sheet2->insertNewRowBefore($row, 1);
                $line_no++;
                $start = $row;
                $sheet2->setCellValue('A' . $row, number_format(round($car->weight)) . " KGS");
                $sheet2->getStyle('A' . $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $sheet2->setCellValue('B' . $row, $car->item_no);
                $sheet2->setCellValue('C' . $row, "USED $car->maker_name");
                $sheet2->getStyle('C' . $row)->getAlignment()->setWrapText(FALSE);
                $sheet2->getStyle('C' . $row)->getAlignment()->setShrinkToFit(TRUE);

                $grade_model = $car->model_name;
                if ($car->imported) {
                    $grade_model .= " $car->grade_name";
                }
                $sheet2->setCellValue('D' . $row, "$grade_model");
                $sheet2->getStyle('D' . $row)->getAlignment()->setWrapText(FALSE);
                $sheet2->getStyle('D' . $row)->getAlignment()->setShrinkToFit(TRUE);

                $sheet2->setCellValue('E' . $row, "1 UNIT");
                $sheet2->getStyle('E' . $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
//                $sheet2->setCellValue('F' . $row, "¥" . number_format($car->amount_custom));
                $sheet2->setCellValue('F' . $row, $car->amount_custom);
                $sheet2->getStyle('F' . $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
//                $sheet2->setCellValue('G' . $row, "¥" . number_format($car->amount_custom));
                $sheet2->setCellValue('G' . $row, $car->amount_custom);
                $sheet2->getStyle('G' . $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $row++;

                $sheet2->insertNewRowBefore($row, 1);
                $line_no++;
                $sheet2->setCellValue('C' . $row, "CHASSIS NO.");
                $sheet2->setCellValue('D' . $row, $car->chassis_no);
                $row++;

                $sheet2->insertNewRowBefore($row, 1);
                $line_no++;
                $sheet2->setCellValue('C' . $row, "ENGINE NO.");
                $sheet2->setCellValue('D' . $row, "$car->engine_no (" . $car->fuel_name . "/$car->engine_size CC)");
                $sheet2->getStyle('D' . $row)->getAlignment()->setWrapText(FALSE);
                $sheet2->getStyle('D' . $row)->getAlignment()->setShrinkToFit(TRUE);
                $row++;

                //Namibia - 212
                // kenya - 20
                $o_e_c = array(20, 212);
                if (in_array($car->to_country_id, $o_e_c)) {
                    $sheet2->insertNewRowBefore($row, 1);
                    $line_no++;
                    $sheet2->setCellValue('C' . $row, "ENGINE NO.");
                    $sheet2->setCellValue('D' . $row, $car->engine_code);
                    $row++;
                }

                //imported and uae
                if ($car->imported && $car->to_country_id == 17) {
                    $sheet2->insertNewRowBefore($row, 1);
                    $line_no++;
                    $sheet2->setCellValue('C' . $row, "STEERING");
                    $sheet2->setCellValue('D' . $row, $car->steering_name);
                    $row++;

                    $sheet2->insertNewRowBefore($row, 1);
                    $line_no++;
                    $sheet2->setCellValue('C' . $row, "COUNTRY");
                    $sheet2->setCellValue('D' . $row, $car->maker_country);
                    $row++;

                    $sheet2->insertNewRowBefore($row, 1);
                    $line_no++;
                    $sheet2->setCellValue('C' . $row, "TYPE");
                    $sheet2->setCellValue('D' . $row, $car->body_type_name);
                    $row++;
                }

                $sheet2->insertNewRowBefore($row, 1);
                $line_no++;

                $reg_year_month_label = "REG YEAR";
                $reg_year_month_value = $car->registration_year;
                //Kenya
                if ($car->to_country_id == 20) {
                    $reg_year_month_label .= "/MONTH";
                    $reg_year_month_value .= "/" . $car->registration_month;
                }
                $sheet2->setCellValue('C' . $row, $reg_year_month_label);
                $sheet2->setCellValue('D' . $row, $reg_year_month_value);
                $row++;

                //Tanzania
                if ($car->to_country_id == 10) {
                    $sheet2->insertNewRowBefore($row, 1);
                    $line_no++;
                    $sheet2->setCellValue('C' . $row, "MFG YEAR:");
                    $sheet2->setCellValue('D' . $row, "$car->manufacture_year");
                    $row++;
                }

                $sheet2->insertNewRowBefore($row, 1);
                $line_no++;
                $sheet2->setCellValue('C' . $row, "COLOR");
                $sheet2->setCellValue('D' . $row, $car->color_name);

                $merge_cells_range[] = 'A' . $start . ':A' . $row;
                $merge_cells_range[] = 'B' . $start . ':B' . $row;
                $merge_cells_range[] = 'E' . $start . ':E' . $row;
                $merge_cells_range[] = 'F' . $start . ':F' . $row;
                $merge_cells_range[] = 'G' . $start . ':G' . $row;

//                if ($cars == 5 || ($cars > 5 && (($cars - 5) % 7 == 0))) {
//                    $pageBreaks[] = 'A' . $row;
//                }
                $row++;
                $no_of_lines[] = array(
                    'key' => "car_" . $car->car_id,
                    'value' => $line_no);
            }

            $i = 1;
            foreach ($container['details']['others'] as $other) {

                $sheet2->insertNewRowBefore($row, 1);
                $line_no++;

                $sheet2->setCellValue('A' . $row, number_format(round($other->weight)) . " KGS");
                $sheet2->getStyle('A' . $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

                $sheet2->setCellValue('C' . $row, "$other->description");

                $merge_cells_range[] = 'C' . $row . ':D' . $row;

                $sheet2->setCellValue('E' . $row, "$other->quantity $other->quantity_unit");
//                $sheet2->setCellValue('F' . $row, "¥" . number_format($other->unit_price));
                $sheet2->setCellValue('F' . $row, $other->unit_price);
                $other_total = $other->quantity * $other->unit_price;
//                $sheet2->setCellValue('G' . $row, "¥" . number_format($other_total));
                $sheet2->setCellValue('G' . $row, $other_total);
                $row++;

                if (!$other->commercial) {

                    $sheet2->insertNewRowBefore($row, 1);
                    $line_no++;
                    $sheet2->setCellValue('E' . $row, "(NO COMMERICAL VALUE)");
                    $sheet2->getStyle('E' . $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    $merge_cells_range[] = 'E' . $row . ':G' . $row;

                    $row++;
                }
                $no_of_lines[] = array(
                    'key' => "other_" . $i,
                    'value' => $line_no);
                $i++;
            }
//            $line_no++;
        }

        $sheet2->insertNewRowBefore($row, 1);
        $line_no++;
        $row++;
        $i = 1;

        foreach ($invoice['others'] as $inv_other) {

            if ($inv_other->amount > 0) {
                if (!stristr($inv_other->description, "freight")) {
                    $sheet2->insertNewRowBefore($row, 1);
                    $line_no++;
                    $inv_other->weight = round($inv_other->weight);

                    if ($inv_other->weight) {
                        $sheet2->setCellValue('A' . $row, number_format(round($inv_other->weight)) . " KGS");
                        $sheet2->getStyle('A' . $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                    }

                    $sheet2->setCellValue('C' . $row, "$inv_other->description");

                    $merge_cells_range[] = 'C' . $row . ':D' . $row;

                    if (!stristr($inv_other->description, "forwarding")) {
                        if (!stristr($inv_other->description, "freight")) {
                            $sheet2->setCellValue('E' . $row, "$inv_other->quantity $inv_other->quantity_unit");
//                    $sheet2->setCellValue('F' . $row, "¥" . number_format($inv_other->unit_price));
                            if (($type == "merged" && stristr($inv_other->description, "inspection"))) {
//                        $sheet2->setCellValue('F' . $row, $inv_other->unit_price);
                                $inv_other->unit_price = $inv_other->amount / $inv_other->quantity;
                            }
                            $sheet2->setCellValue('F' . $row, $inv_other->unit_price);
                        }
                    }

                    if (!stristr($inv_other->description, "freight")) {

                        if ($type == "merged" && stristr($inv_other->description, "inspection")) {
                            $inv_other_total = $inv_other->amount;
                        } else {
                            $inv_other_total = $inv_other->quantity * $inv_other->unit_price;
                        }
//                $sheet2->setCellValue('G' . $row, "¥" . number_format($inv_other_total));
                        $sheet2->setCellValue('G' . $row, $inv_other_total);
                    }
                    $row++;

                    if (stristr($inv_other->description, "forwarding")) {
                        $sheet2->insertNewRowBefore($row, 1);
                        $line_no++;
                        $sheet2->setCellValue('C' . $row, $forwarding_charges_text);
                        $merge_cells_range[] = 'C' . $row . ':D' . $row;
                        $row++;
                    }

                    if (!$inv_other->commercial) {

                        if (!stristr($inv_other->description, "freight")) {
                            $sheet2->insertNewRowBefore($row, 1);
                            $line_no++;
                            $sheet2->setCellValue('E' . $row, "(NO COMMERICAL VALUE)");
                            $merge_cells_range[] = 'E' . $row . ':G' . $row;
                            $row++;
                        }
                    }
                    if (stristr($inv_other->description, "inspection")) {
                        $sheet2->insertNewRowBefore($row, 1);
                        $line_no++;
                        $row++;
                    }

                    $no_of_lines[] = array(
                        'key' => "inv_other_" . $i,
                        'value' => ($line_no - 1));
                }
            }
        }

        $line_no += 13;
        $no_of_lines[] = array(
            'key' => "inv_other_" . $i,
            'value' => ($line_no - 1));
        $invoice_sheet_last_row = $row;

        $pageBreaks = array();
        $page_lines = 51;
        foreach ($no_of_lines as $index => $node) {

            if (isset($no_of_lines[$index + 1])) {

                if ($node['value'] <= $page_lines && ($no_of_lines[$index + 1]['value'] > $page_lines)) {
                    if (strstr($node['key'], "car")) {
                        $pageBreaks[] = "A" . ($node['value']);
                    } else if (strstr($node['key'], "containers")) {
                        if ($no_of_lines[$index + 1]['value'] > $page_lines) {
                            $pageBreaks[] = "A" . ($no_of_lines[$index - 1]['value']);
                        } else {
                            $pageBreaks[] = "A" . ($node['value']);
                        }
                    } else {
                        $pageBreaks[] = "A" . ($node['value'] - 1);
                    }
                    $page_lines += 51;
                }
            }
        }

        foreach ($merge_cells_range as $cell_range) {
            $sheet2->mergeCells($cell_range);
        }

        $sheet2->getPageSetup()->setRowsToRepeatAtTop(array(13, 14));

        /**
         * ***************************************************************** */
        $sheet2->getPageSetup()->setFitToWidth(1);
        $sheet2->getPageSetup()->setFitToHeight(0);
        $sheet2->getPageSetup()->setHorizontalCentered(1);
        foreach ($pageBreaks as $pb) {
            $sheet2->setBreak($pb, PHPExcel_Worksheet::BREAK_ROW);
        }

        return $objPHPExcel;
    }

    public function generate_buyers_invoice_sheet($invoice_id, $invoice) {

        $result_data = array();
        $result_data['bl_no'] = $invoice['master']->bl_no;
        $result_data['booking_no'] = $invoice['master']->booking_no;
        $result_data['invoice_no'] = $invoice['master']->invoice_no;

        $invoice_year_id = $invoice['invoice_year_details']->invoice_year_id;

        $buyers_consignee_office_name = $invoice['consignees']['buyers_consignee']->office_name;
        $buyers_consignee_office_address = str_replace("\m", "\n", $invoice['consignees']['buyers_consignee']->office_address);

        if (stristr($invoice['master']->to_port_name, "Durban")) {
            $invoice['master']->to_port_name = "DURBAN";
        }
        $weekly_name = "";
        if (!empty($invoice['master']->weekly_name)) {
            $pakistan_ids = array(282, 317);

            if (!in_array($invoice['master']->to_country_id, $pakistan_ids)) {
                $weekly_name = substr($invoice['master']->weekly_name, 7);
            } else {
                $weekly_name = $invoice['master']->weekly_name;
            }
        } else {
            $weekly_name = "n/a";
        }


        //214 - Mozambique
        if ($invoice['master']->to_country_id == 214) {
            $str_pos = strpos($buyers_consignee_office_address, "E-mail:");
            $buyers_consignee_office_address = substr($buyers_consignee_office_address, 0, $str_pos);
        }

        $from_address_B8 = "";
        if ($invoice['master']->from_city_id == 255) {
            $from_address_B8 = "HAKATA, JAPAN";
        } else if ($invoice['master']->from_city_id == 308) {
            $from_address_B8 = "YOKOHAMA VIA TOMAKOMAI, JAPAN";
        } else {
            //20 - Kenya
            //212 - Namibia
            //10 - Tanzania
            //279 - Uganda
            if ($invoice['master']->to_country_id == 20 || $invoice['master']->to_country_id == 212 || $invoice['master']->to_country_id == 10 || $invoice['master']->to_country_id == 279) {
                $invoice['master']->place_of_receipt = str_ireplace(", CY", "", $invoice['master']->place_of_receipt);
                $from_address_B8 .= $invoice['master']->place_of_receipt . ", " . $invoice['master']->from_country_name;
            } else {
                $from_address_B8 .= $invoice['master']->from_port_name . ", " . $invoice['master']->from_country_name;
            }
        }

        $inputFile = FCPATH . 'downloads/invoices/templates/inv_temp_buyers_invoice.xlsx';

        $reader = new PHPExcel_Reader_Excel2007();
        $objPHPExcel = $reader->load($inputFile);
        /**
         * * ******************************************** */
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->setActiveSheetIndex(0);
        $sheet3 = $objPHPExcel->getActiveSheet();

        $all_containers_total_units_count = 0;
        $all_containers_total_pcs_count = 0;
        $all_containers_total_amount = 0;
        $all_containers_total_g_weight = 0;
        $all_containers_no_commercial_total_amount = 0;

        $total_containers = 0;
        $total_cars = 0;
        $invoice_total_amount = 0;
        $invoice_total_weight = 0;
        $invoice_total_units_count = 0;
        $invoice_total_pcs_count = 0;
        $invoice_no_commercial_total_amount = 0;
        $invoice_str_quantity = "";
        $str_invoice_no_commercial = "";

        $row = 28;
        foreach ($invoice['containers'] as $container) {
            $container_units_count = 0;
            $container_pcs_count = 0;
            $container_g_weight = 0;
            $str_quanity = "";

            $total_containers++;

            foreach ($container['details']['cars'] as $car) {
                $total_cars++;
                $container_units_count++;
                $container_g_weight += $car->weight;
                $all_containers_total_amount += $car->amount_buyer;
            }
            foreach ($container['details']['others'] as $other) {
                $other_amount = $other->unit_price * $other->quantity;
                if ($other->commercial) {
                    $all_containers_total_amount += $other_amount;
                } else {
                    $all_containers_no_commercial_total_amount += $other_amount;
                }
                if ($other->quantity_unit == "UNITS") {
                    $container_units_count += $other->quantity;
                } else if ($other->quantity_unit == "PCS") {
                    $container_pcs_count += $other->quantity;
                }
                $container_g_weight += $other->weight;
            }
            $all_containers_total_units_count += $container_units_count;
            $all_containers_total_pcs_count += $container_pcs_count;


            if ($container_units_count && $container_pcs_count) {
                $str_quanity = "$container_units_count UNITS & $container_pcs_count PCS";
            } else if ($container_units_count && !$container_pcs_count) {
                $str_quanity = "$container_units_count UNITS";
            } else if (!$container_units_count && $container_pcs_count) {
                $str_quanity = "$container_pcs_count PCS";
            }

            $all_containers_total_g_weight += $container_g_weight;
            $row++;
        }
        $row += 2;

        $str_quanity_total = "";
        if ($all_containers_total_units_count && $all_containers_total_pcs_count) {
            $str_quanity_total = "$all_containers_total_units_count UNITS & $all_containers_total_pcs_count PCS";
        } else if ($all_containers_total_units_count && !$all_containers_total_pcs_count) {
            $str_quanity_total = "$all_containers_total_units_count UNITS";
        } else if (!$all_containers_total_units_count && $all_containers_total_pcs_count) {
            $str_quanity_total = "$all_containers_total_pcs_count PCS";
        }

        $invoice_total_amount += $all_containers_total_amount;
        $invoice_total_weight += $all_containers_total_g_weight;

        $invoice_total_units_count += $all_containers_total_units_count;
        $invoice_total_pcs_count += $all_containers_total_pcs_count;
        $invoice_no_commercial_total_amount += $all_containers_no_commercial_total_amount;

//        if (isset($invoice['others'])) {
        $forwarding_charges_text = "";
        foreach ($invoice['others'] as $inv_other) {
            if (!stristr($inv_other->description, "forwarding") && !stristr($inv_other->description, "freight")) {
//            if (!stristr($inv_other->description, "freight")) {
                $inv_amount = 0;
                $inv_amount = $inv_other->quantity * $inv_other->unit_price;
                if ($inv_other->commercial) {
                    $invoice_total_amount += $inv_amount;
                } else {
                    $invoice_no_commercial_total_amount += $inv_amount;
                }

                if (!stristr($inv_other->description, "inspection")) {
                    $invoice_total_weight += $inv_other->weight;
                    if ($inv_other->quantity_unit == "UNITS") {
                        $invoice_total_units_count += $inv_other->quantity;
                    } else if ($inv_other->quantity_unit == "PCS") {
                        $invoice_total_pcs_count += $inv_other->quantity;
                    }
                }
            } else if (stristr($inv_other->description, "forwarding")) {
                $fwd_charges = number_format($inv_other->unit_price);

                switch ($invoice_year_id) {
                    case 1:
                        $forwarding_charges_text = "(JPY$fwd_charges PER UNIT)";
                        break;
                    case 2:
                        $forwarding_charges_text = "(JPY$fwd_charges PER CONTAINER)";
                        break;
                }

                $inv_amount = 0;
                $inv_amount = $inv_other->quantity * $inv_other->unit_price;
                $invoice_total_amount += $inv_amount;
            } else if (stristr($inv_other->description, "freight")) {
                if ($invoice['master']->to_country_id != 310) {
                    $sheet3->setCellValue('C21', 'FREIGHT CHARGES (¥' . number_format($inv_other->unit_price) . ' PER VAN)');
                    $sheet3->setCellValue('E21', $total_containers . ' VANS');
                    $total_freight = $inv_other->unit_price * $total_containers;
                    $sheet3->setCellValue('G21', '¥' . number_format($total_freight));
                }
            }
        }
//        }

        if ($invoice_total_units_count && $invoice_total_pcs_count) {
            $invoice_str_quantity = "$invoice_total_units_count UNITS & $invoice_total_pcs_count PCS";
        } else if ($invoice_total_units_count && !$invoice_total_pcs_count) {
            $invoice_str_quantity = "$invoice_total_units_count UNITS";
        } else if (!$invoice_total_units_count && $invoice_total_pcs_count) {
            $invoice_str_quantity = "$invoice_total_pcs_count PCS";
        }
        if ($invoice_no_commercial_total_amount) {
            $str_invoice_no_commercial = "(NO COMMERICAL VALUE ¥$invoice_no_commercial_total_amount)";
        }

        /*         * ********************************************************************* */

        $merge_cells_range = array();


        $sheet3->setCellValue('E4', "DATE  " . date_to_invoice($invoice['master']->invoice_date));
        $sheet3->setCellValue('E5', "NO. " . $invoice['master']->invoice_no);
        $sheet3->setCellValue('B6', $invoice['master']->invoice_type_name);
        $sheet3->setCellValue('B7', "\"" . $invoice['master']->vessel_name . "\"  VOY. " . $invoice['master']->voyage_no);

        $sheet3->setCellValue('E6', $weekly_name);
        $sheet3->setCellValue('F7', date_to_invoice($invoice['master']->shipment_date));

        $sheet3->setCellValue('B8', $from_address_B8);

        if ($invoice['master']->to_country_id == 359) {
            $f8 = "SWAZILAND VIA DURBAN";
        } else if ($invoice['master']->to_country_id == 353) {
            $f8 = "LESOTHO VIA DURBAN";
        } else if ($invoice['master']->to_country_id == 396) {
            $f8 = "MONGOLIA VIA XINGANG, CHINA";
        } else {
            if ($invoice['master']->to_country_id == 317) {
                $invoice['master']->to_country_name = "PAKISTAN";
            }

            $f8 = $invoice['master']->to_port_name . ", " . $invoice['master']->to_country_name;
        }
        $sheet3->setCellValue('F8', $f8);

        $sheet3->setCellValue('B9', $buyers_consignee_office_name);
        $sheet3->setCellValue('B10', $buyers_consignee_office_address);
        $sheet3->setCellValue('B12', $invoice['master']->payment_type);
        $sheet3->setCellValue('D12', "B/L NO " . $invoice['master']->bl_no);
        $sheet3->setCellValue('E12', "BOOKING NO. " . $invoice['master']->booking_no);
        $sheet3->setCellValue('A15', $invoice['master']->marks);
        $sheet3->setCellValue('C15', $invoice['master']->invoice_type_name);
        $sheet3->setCellValue('F15', $invoice['master']->info);
        $sheet3->setCellValue('A22', number_format($invoice_total_weight) . " KGS");
        $sheet3->setCellValue('C22', "40' x $total_containers CONTAINERS");
        $sheet3->setCellValue('E22', $invoice_str_quantity);
        $sheet3->setCellValue('G22', "¥" . number_format($invoice_total_amount));

        $sheet3->setCellValue('E23', $str_invoice_no_commercial);
        $sheet3->getStyle('E23')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

        $no_of_lines = array();
        $line_no = 16;
        $row = 17;
        $cars = 0;
        $pageBreaks = array();

        foreach ($invoice['containers'] as $container) {

            $sheet3->insertNewRowBefore($row, 1);
            $sheet3->setCellValue('A' . $row, $container['container_no']);
            $sheet3->getStyle('A' . $row)->getAlignment()->setWrapText(FALSE);
            $sheet3->getStyle('A' . $row)->getAlignment()->setShrinkToFit(TRUE);
            $row++;
            $line_no++;

            $no_of_lines[] = array(
                'key' => "containers_" . $container['container_no'],
                'value' => $line_no);
            foreach ($container['details']['cars'] as $car) {
                $cars++;
                $sheet3->insertNewRowBefore($row, 1);
                $line_no++;
                $start = $row;
                $sheet3->setCellValue('A' . $row, number_format(round($car->weight)) . " KGS");
                $sheet3->getStyle('A' . $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $sheet3->setCellValue('B' . $row, $car->item_no);
                $sheet3->setCellValue('C' . $row, "USED $car->maker_name");
                $sheet3->getStyle('C' . $row)->getAlignment()->setWrapText(FALSE);
                $sheet3->getStyle('C' . $row)->getAlignment()->setShrinkToFit(TRUE);

                $grade_model = $car->model_name;
                if ($car->imported) {
                    $grade_model .= " $car->grade_name";
                }
                $sheet3->setCellValue('D' . $row, "$grade_model");
                $sheet3->getStyle('D' . $row)->getAlignment()->setWrapText(FALSE);
                $sheet3->getStyle('D' . $row)->getAlignment()->setShrinkToFit(TRUE);

                $sheet3->setCellValue('E' . $row, "1 UNIT");
                $sheet3->getStyle('E' . $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $sheet3->setCellValue('F' . $row, "¥" . number_format($car->amount_buyer));
                $sheet3->getStyle('F' . $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $sheet3->setCellValue('G' . $row, "¥" . number_format($car->amount_buyer));
                $sheet3->getStyle('G' . $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $row++;

                $sheet3->insertNewRowBefore($row, 1);
                $line_no++;
                $sheet3->setCellValue('C' . $row, "CHASSIS NO.");
                $sheet3->setCellValue('D' . $row, $car->chassis_no);
                $row++;

                $sheet3->insertNewRowBefore($row, 1);
                $line_no++;
                $sheet3->setCellValue('C' . $row, "ENGINE NO.");
                $sheet3->setCellValue('D' . $row, $car->engine_no . " (" . $car->fuel_name . "/" . $car->engine_size . " CC)");
                $sheet3->getStyle('D' . $row)->getAlignment()->setWrapText(FALSE);
                $sheet3->getStyle('D' . $row)->getAlignment()->setShrinkToFit(TRUE);
                $row++;

                //Namibia - 212
                // kenya - 20
                $o_e_c = array(20, 212);
                if (in_array($car->to_country_id, $o_e_c)) {
                    $sheet3->insertNewRowBefore($row, 1);
                    $line_no++;
                    $sheet3->setCellValue('C' . $row, "ENGINE NO.");
                    $sheet3->setCellValue('D' . $row, $car->engine_code);
                    $row++;
                }

                //imported and uae
                if ($car->imported && $car->to_country_id == 17) {
                    $sheet3->insertNewRowBefore($row, 1);
                    $line_no++;
                    $sheet3->setCellValue('C' . $row, "STEERING");
                    $sheet3->setCellValue('D' . $row, $car->steering_name);
                    $row++;

                    $sheet3->insertNewRowBefore($row, 1);
                    $line_no++;
                    $sheet3->setCellValue('C' . $row, "COUNTRY");
                    $sheet3->setCellValue('D' . $row, $car->maker_country);
                    $row++;

                    $sheet3->insertNewRowBefore($row, 1);
                    $line_no++;
                    $sheet3->setCellValue('C' . $row, "TYPE");
                    $sheet3->setCellValue('D' . $row, $car->body_type_name);
                    $row++;
                }

                $sheet3->insertNewRowBefore($row, 1);
                $line_no++;

                $reg_year_month_label = "REG YEAR";
                $reg_year_month_value = $car->registration_year;
                //Kenya
                if ($car->to_country_id == 20) {
                    $reg_year_month_label .= "/MONTH";
                    $reg_year_month_value .= "/" . $car->registration_month;
                }
                $sheet3->setCellValue('C' . $row, $reg_year_month_label);
                $sheet3->setCellValue('D' . $row, $reg_year_month_value);
                $row++;

                //Tanzania
                if ($car->to_country_id == 10) {
                    $sheet3->insertNewRowBefore($row, 1);
                    $line_no++;
                    $sheet3->setCellValue('C' . $row, "MFG YEAR:");
                    $sheet3->setCellValue('D' . $row, "$car->manufacture_year");
                    $row++;
                }

                $sheet3->insertNewRowBefore($row, 1);
                $line_no++;
                $sheet3->setCellValue('C' . $row, "COLOR");
                $sheet3->setCellValue('D' . $row, $car->color_name);

                $merge_cells_range[] = 'A' . $start . ':A' . $row;
                $merge_cells_range[] = 'B' . $start . ':B' . $row;
                $merge_cells_range[] = 'E' . $start . ':E' . $row;
                $merge_cells_range[] = 'F' . $start . ':F' . $row;
                $merge_cells_range[] = 'G' . $start . ':G' . $row;

//                if ($cars == 5 || ($cars > 5 && (($cars - 5) % 7 == 0))) {
//                    $pageBreaks[] = 'A' . $row;
//                }
                $row++;
                $no_of_lines[] = array(
                    'key' => "car_" . $car->car_id,
                    'value' => $line_no);
            }

            $i = 1;
            foreach ($container['details']['others'] as $other) {

                $sheet3->insertNewRowBefore($row, 1);
                $line_no++;

                $sheet3->setCellValue('A' . $row, round($other->weight) . "KGS");
                $sheet3->getStyle('A' . $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

                $sheet3->setCellValue('C' . $row, "$other->description");

                $merge_cells_range[] = 'C' . $row . ':D' . $row;

                $sheet3->setCellValue('E' . $row, "$other->quantity $other->quantity_unit");
                $sheet3->setCellValue('F' . $row, "¥" . number_format($other->unit_price));
                $other_total = $other->quantity * $other->unit_price;
                $sheet3->setCellValue('G' . $row, "¥" . number_format($other_total));
                $row++;

                if (!$other->commercial) {

                    $sheet3->insertNewRowBefore($row, 1);
                    $line_no++;
                    $sheet3->setCellValue('E' . $row, "(NO COMMERICAL VALUE)");
                    $sheet3->getStyle('E' . $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    $merge_cells_range[] = 'E' . $row . ':G' . $row;

                    $row++;
                }
                $no_of_lines[] = array(
                    'key' => "other_" . $i,
                    'value' => $line_no);
                $i++;
            }
        }
        $sheet3->insertNewRowBefore($row, 1);
        $line_no++;
        $row++;
        $i = 1;
//        if (isset($invoice['others'])) {
        foreach ($invoice['others'] as $inv_other) {

            if ($inv_other->amount > 0) {
//                if (!stristr($inv_other->description, "Forwarding") && !stristr($inv_other->description, "freight")) {
//                    $sheet3->insertNewRowBefore($row, 1);
//                    $line_no++;
//                    $inv_other->weight = round($inv_other->weight);
//
//                    if ($inv_other->weight) {
//                        $sheet3->setCellValue('A' . $row, round($inv_other->weight) . "KGS");
//                        $sheet3->getStyle('A' . $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
//                    }
//
//
//                    $sheet3->setCellValue('C' . $row, "$inv_other->description");
//                    $merge_cells_range[] = 'C' . $row . ':D' . $row;
//
//                    $sheet3->setCellValue('E' . $row, "$inv_other->quantity $inv_other->quantity_unit");
//                    $sheet3->setCellValue('F' . $row, "¥$inv_other->unit_price");
//                    $inv_other_total = $inv_other->quantity * $inv_other->unit_price;
//                    $sheet3->setCellValue('G' . $row, "¥$inv_other_total");
//                    $row++;
//
//                    if (!$inv_other->commercial) {
//
//                        $sheet3->insertNewRowBefore($row, 1);
//                        $line_no++;
//                        $sheet3->setCellValue('E' . $row, "(NO COMMERICAL VALUE)");
//                        $merge_cells_range[] = 'E' . $row . ':G' . $row;
//                        $row++;
//                    }
//                }

                if (!stristr($inv_other->description, "freight")) {
                    $sheet3->insertNewRowBefore($row, 1);
                    $line_no++;
                    $inv_other->weight = round($inv_other->weight);

                    if ($inv_other->weight) {
                        $sheet3->setCellValue('A' . $row, number_format(round($inv_other->weight)) . " KGS");
                        $sheet3->getStyle('A' . $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                    }

                    $sheet3->setCellValue('C' . $row, "$inv_other->description");

                    $merge_cells_range[] = 'C' . $row . ':D' . $row;

                    if (!stristr($inv_other->description, "forwarding")) {
                        if (!stristr($inv_other->description, "freight")) {
                            $sheet3->setCellValue('E' . $row, "$inv_other->quantity $inv_other->quantity_unit");
//                    $sheet2->setCellValue('F' . $row, "¥" . number_format($inv_other->unit_price));
//                            if (($type == "merged" && stristr($inv_other->description, "inspection"))) {
//                        $sheet2->setCellValue('F' . $row, $inv_other->unit_price);
                            $inv_other->unit_price = $inv_other->amount / $inv_other->quantity;
//                            }
                            $sheet3->setCellValue('F' . $row, $inv_other->unit_price);
                        }
                    }

                    if (!stristr($inv_other->description, "freight")) {

                        $inv_other_total = $inv_other->quantity * $inv_other->unit_price;

//                $sheet2->setCellValue('G' . $row, "¥" . number_format($inv_other_total));
                        $sheet3->setCellValue('G' . $row, $inv_other_total);
                    }
                    $row++;

                    if (stristr($inv_other->description, "forwarding")) {
                        $sheet3->insertNewRowBefore($row, 1);
                        $line_no++;
                        $sheet3->setCellValue('C' . $row, $forwarding_charges_text);
                        $merge_cells_range[] = 'C' . $row . ':D' . $row;
                        $row++;
                    }

                    if (!$inv_other->commercial) {

                        if (!stristr($inv_other->description, "freight")) {
                            $sheet3->insertNewRowBefore($row, 1);
                            $line_no++;
                            $sheet3->setCellValue('E' . $row, "(NO COMMERICAL VALUE)");
                            $merge_cells_range[] = 'E' . $row . ':G' . $row;
                            $row++;
                        }
                    }
                    if (stristr($inv_other->description, "inspection")) {
                        $sheet3->insertNewRowBefore($row, 1);
                        $line_no++;
                        $row++;
                    }

                    $no_of_lines[] = array(
                        'key' => "inv_other_" . $i,
                        'value' => ($line_no - 1));
                }

                $no_of_lines[] = array(
                    'key' => "inv_other_" . $i,
                    'value' => ($line_no - 1));
            }
        }
//        }
        $line_no += 13;
        $no_of_lines[] = array(
            'key' => "inv_other_" . $i,
            'value' => ($line_no - 1));
        $invoice_sheet_last_row = $row;

        $pageBreaks = array();
        $page_lines = 51;
        foreach ($no_of_lines as $index => $node) {
//            $modulus = $node['value'] % 52;
//            echo("$node[key] - $node[value] - $modulus<br>");
//            echo("$node[key] - $node[value]<br>");

            if (isset($no_of_lines[$index + 1])) {
//                echo("Page Lines - $page_lines<br>"
//                        . "Current - $node[value] <br>"
//                        . " Next " . $no_of_lines[$index + 1]['value'] . "<br>");

                if ($node['value'] <= $page_lines && ($no_of_lines[$index + 1]['value'] > $page_lines)) {
                    if (strstr($node['key'], "car")) {
                        $pageBreaks[] = "A" . ($node['value']);
//                        echo("TRUE = Car - A" . $node['value'] . "<br>");
                    } else if (strstr($node['key'], "containers")) {
                        if ($no_of_lines[$index + 1]['value'] > $page_lines) {
                            $pageBreaks[] = "A" . ($no_of_lines[$index - 1]['value']);
//                            echo("Container-1 - A" . ($no_of_lines[$index - 1]['value']) . "<br>");
                        } else {
                            $pageBreaks[] = "A" . ($node['value']);
//                            echo("TRUE = Container-2 - A" . $node['value'] . "<br>");
                        }
                    } else {
                        $pageBreaks[] = "A" . ($node['value'] - 1);
//                        echo("TRUE = Other - A" . $node['value'] . "<br>");
                    }
                    $page_lines += 51;
                }
//                echo("<hr>");
            }
        }

        foreach ($merge_cells_range as $cell_range) {
            $sheet3->mergeCells($cell_range);
        }

        $sheet3->getPageSetup()->setRowsToRepeatAtTop(array(13, 13));

        /**
         * * *************************************************************** */
        $sheet3->getPageSetup()->setFitToWidth(1);
        $sheet3->getPageSetup()->setFitToHeight(0);
        $sheet3->getPageSetup()->setHorizontalCentered(1);
        foreach ($pageBreaks as $pb) {
            $sheet3->setBreak($pb, PHPExcel_Worksheet::BREAK_ROW);
        }
//        die;
        return $objPHPExcel;
    }

    //Karachi buyers invoice
    public function generate_buyers_invoice_kch_sheet($invoice_id, $invoice) {

        $result_data = array();
        $result_data['bl_no'] = $invoice['master']->bl_no;
        $result_data['booking_no'] = $invoice['master']->booking_no;
        $result_data['invoice_no'] = $invoice['master']->invoice_no;

        $buyers_consignee_office_name = $invoice['consignees']['buyers_consignee']->office_name;
        $buyers_consignee_office_address = str_replace("\m", "\n", $invoice['consignees']['buyers_consignee']->office_address);

        if (!empty($invoice['master']->weekly_name)) {
            $weekly_name = substr($invoice['master']->weekly_name, 7);
        } else {
            $weekly_name = "n/a";
        }

        $from_address_B8 = "";
        if ($invoice['master']->from_city_id == 255) {
            $from_address_B8 = "HAKATA, JAPAN";
        } else if ($invoice['master']->from_city_id == 308) {
            $from_address_B8 = "YOKOHAMA VIA TOMAKOMAI, JAPAN";
        } else {
            $from_address_B8 .= $invoice['master']->from_port_name . ", " . $invoice['master']->from_country_name;
        }


        $inputFile = FCPATH . 'downloads/invoices/templates/inv_temp_buyers_invoice_kch.xlsx';

        $reader = new PHPExcel_Reader_Excel2007();
        $objPHPExcel = $reader->load($inputFile);
        /**
         * * ******************************************** */
//        $objPHPExcel->setActiveSheetIndex(0);

        $all_containers_total_units_count = 0;
        $all_containers_total_pcs_count = 0;
        $all_containers_total_amount = 0;
        $all_containers_total_g_weight = 0;
        $all_containers_no_commercial_total_amount = 0;

        $total_containers = 0;
        $total_cars = 0;
        $invoice_total_amount = 0;
        $invoice_total_weight = 0;
        $invoice_total_units_count = 0;
        $invoice_total_pcs_count = 0;
        $invoice_no_commercial_total_amount = 0;
        $invoice_str_quantity = "";
        $str_invoice_no_commercial = "";

        $row = 28;
        foreach ($invoice['containers'] as $container) {
            $container_units_count = 0;
            $container_pcs_count = 0;
            $container_g_weight = 0;
            $str_quanity = "";

            $total_containers++;

            foreach ($container['details']['cars'] as $car) {
                $total_cars++;
                $container_units_count++;
                $container_g_weight += $car->weight;
                $all_containers_total_amount += $car->amount_buyer;
            }
            foreach ($container['details']['others'] as $other) {
                $other_amount = $other->unit_price * $other->quantity;
                if ($other->commercial) {
                    $all_containers_total_amount += $other_amount;
                } else {
                    $all_containers_no_commercial_total_amount += $other_amount;
                }
                if ($other->quantity_unit == "UNITS") {
                    $container_units_count += $other->quantity;
                } else if ($other->quantity_unit == "PCS") {
                    $container_pcs_count += $other->quantity;
                }
                $container_g_weight += $other->weight;
            }
            $all_containers_total_units_count += $container_units_count;
            $all_containers_total_pcs_count += $container_pcs_count;


            if ($container_units_count && $container_pcs_count) {
                $str_quanity = "$container_units_count UNITS & $container_pcs_count PCS";
            } else if ($container_units_count && !$container_pcs_count) {
                $str_quanity = "$container_units_count UNITS";
            } else if (!$container_units_count && $container_pcs_count) {
                $str_quanity = "$container_pcs_count PCS";
            }

            $all_containers_total_g_weight += $container_g_weight;
            $row++;
        }
        $row += 2;

        $str_quanity_total = "";
        if ($all_containers_total_units_count && $all_containers_total_pcs_count) {
            $str_quanity_total = "$all_containers_total_units_count UNITS & $all_containers_total_pcs_count PCS";
        } else if ($all_containers_total_units_count && !$all_containers_total_pcs_count) {
            $str_quanity_total = "$all_containers_total_units_count UNITS";
        } else if (!$all_containers_total_units_count && $all_containers_total_pcs_count) {
            $str_quanity_total = "$all_containers_total_pcs_count PCS";
        }

        $invoice_total_amount += $all_containers_total_amount;
        $invoice_total_weight += $all_containers_total_g_weight;

        $invoice_total_units_count += $all_containers_total_units_count;
        $invoice_total_pcs_count += $all_containers_total_pcs_count;
        $invoice_no_commercial_total_amount += $all_containers_no_commercial_total_amount;

        foreach ($invoice['others'] as $inv_other) {
            if (!stristr($inv_other->description, "forwarding") && !stristr($inv_other->description, "freight")) {
                $inv_amount = 0;
                $inv_amount = $inv_other->quantity * $inv_other->unit_price;
                if ($inv_other->commercial) {
                    $invoice_total_amount += $inv_amount;
                } else {
                    $invoice_no_commercial_total_amount += $inv_amount;
                }

                if (!stristr($inv_other->description, "inspection")) {
                    $invoice_total_weight += $inv_other->weight;
                    if ($inv_other->quantity_unit == "UNITS") {
                        $invoice_total_units_count += $inv_other->quantity;
                    } else if ($inv_other->quantity_unit == "PCS") {
                        $invoice_total_pcs_count += $inv_other->quantity;
                    }
                }
            }
        }

        if ($invoice_total_units_count && $invoice_total_pcs_count) {
            $invoice_str_quantity = "$invoice_total_units_count UNITS & $invoice_total_pcs_count PCS";
        } else if ($invoice_total_units_count && !$invoice_total_pcs_count) {
            $invoice_str_quantity = "$invoice_total_units_count UNITS";
        } else if (!$invoice_total_units_count && $invoice_total_pcs_count) {
            $invoice_str_quantity = "$invoice_total_pcs_count PCS";
        }
        if ($invoice_no_commercial_total_amount) {
            $str_invoice_no_commercial = "(NO COMMERICAL VALUE ¥$invoice_no_commercial_total_amount)";
        }

        /*         * ********************************************************************* */

        $merge_cells_range = array();

        $objPHPExcel->setActiveSheetIndex(0);
        $sheet3 = $objPHPExcel->getActiveSheet();
        $sheet3->setCellValue('E4', "DATE  " . date_to_invoice($invoice['master']->invoice_date));
        $sheet3->setCellValue('E5', "NO. " . $invoice['master']->invoice_no);
        $sheet3->setCellValue('B6', $invoice['master']->invoice_type_name);
        $sheet3->setCellValue('B7', "\"" . $invoice['master']->vessel_name . "\"  VOY. " . $invoice['master']->voyage_no);

        if (!empty($invoice['master']->weekly_name)) {
            $invoice['master']->weekly_name = substr($invoice['master']->weekly_name, 7);
        } else {
            $invoice['master']->weekly_name = "n/a";
        }
        $sheet3->setCellValue('E6', $weekly_name);
        $sheet3->setCellValue('F7', " " . date_to_invoice($invoice['master']->shipment_date));


        $sheet3->setCellValue('B8', $from_address_B8);

        if ($invoice['master']->to_country_id == 317) {
            $invoice['master']->to_country_name = "PAKISTAN";
        }

        $sheet3->setCellValue('F8', $invoice['master']->to_port_name . ", " . $invoice['master']->to_country_name);

        $sheet3->setCellValue('B9', $buyers_consignee_office_name);
        $sheet3->setCellValue('B10', $buyers_consignee_office_address);
        $sheet3->setCellValue('B12', $invoice['master']->payment_type);
        $sheet3->setCellValue('D12', "B/L NO " . $invoice['master']->bl_no);
        $sheet3->setCellValue('E12', "BOOKING NO. " . $invoice['master']->booking_no);
        $sheet3->setCellValue('A15', $invoice['master']->marks);
        $sheet3->setCellValue('C15', $invoice['master']->invoice_type_name);
        $sheet3->setCellValue('F15', $invoice['master']->info);
//        $sheet3->setCellValue('A21', number_format($invoice_total_weight) . " KGS");
//        $sheet3->setCellValue('C21', "40' x $total_containers CONTAINERS");
//        $sheet3->setCellValue('E21', $invoice_str_quantity);
//        $sheet3->setCellValue('G21', "¥" . number_format($invoice_total_amount));
//        $sheet3->setCellValue('E23', $str_invoice_no_commercial);
        $sheet3->getStyle('E23')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

        $row = 16;
        $cars = 0;
        $pageBreaks = array();

        foreach ($invoice['containers'] as $container) {

            foreach ($container['details']['cars'] as $car) {
                $sheetTemp = $sheet3->copy();
                $sheetTemp->setTitle("customer_" . $car->chassis_no);

                $sheetTemp->setCellValue('A17', $container['container_no']);
                $sheetTemp->setCellValue('A18', number_format(round($car->weight)) . " KGS");
//                echo($car->chassis_no);
//                $sheetTemp->getStyle('A17')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $sheetTemp->setCellValue('A50', number_format(round($car->weight)) . " KGS");
//                $sheetTemp->getStyle('A50')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

                $sheetTemp->setCellValue('B18', $car->item_no);
                $sheetTemp->setCellValue('C18', "USED $car->maker_name");
//                $sheetTemp->getStyle('C17')->getAlignment()->setWrapText(FALSE);
//                $sheetTemp->getStyle('C17')->getAlignment()->setShrinkToFit(TRUE);

                $grade_model = $car->model_name;
                if ($car->imported) {
                    $grade_model .= " $car->grade_name";
                }

                $sheetTemp->setCellValue('D18', "$grade_model");

//                $sheetTemp->getStyle('D18')->getAlignment()->setWrapText(FALSE);
//                $sheetTemp->getStyle('D18')->getAlignment()->setShrinkToFit(TRUE);

                $sheetTemp->setCellValue('E18', "1 UNIT");
//                $sheetTemp->getStyle('E18')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $sheetTemp->setCellValue('F18', "¥" . number_format($car->amount_buyer));
//                $sheetTemp->getStyle('F18')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $sheetTemp->setCellValue('G18', "¥" . number_format($car->amount_buyer));
//                $sheetTemp->getStyle('G18')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $sheetTemp->setCellValue('G50', "¥" . number_format($car->amount_buyer));
//                $sheetTemp->getStyle('G50')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

                $sheetTemp->setCellValue('C19', "CHASSIS NO.");
                $sheetTemp->setCellValue('D19', $car->chassis_no);

                $sheetTemp->setCellValue('C20', "ENGINE NO.");
                $sheetTemp->setCellValue('D20', $car->engine_no . "(" . $car->fuel_name . "/" . $car->engine_size . " CC)");
//                $sheetTemp->getStyle('D20')->getAlignment()->setShrinkToFit(TRUE);

                $reg_year_month_label = "REG YEAR";
                $reg_year_month_value = $car->registration_year;

                $sheetTemp->setCellValue('C21', $reg_year_month_label);
                $sheetTemp->setCellValue('D21', $reg_year_month_value);
                $row++;

                $sheetTemp->setCellValue('C22', "COLOR");
                $sheetTemp->setCellValue('D22', $car->color_name);

                $sheetTemp->mergeCells('A18:A22');
                $sheetTemp->mergeCells('B18:B22');
                $sheetTemp->mergeCells('E18:E22');
                $sheetTemp->mergeCells('F18:F22');
                $sheetTemp->mergeCells('G18:G22');

                $sheetTemp->getPageSetup()->setFitToWidth(1);
                $sheetTemp->getPageSetup()->setFitToHeight(0);
                $sheetTemp->getPageSetup()->setHorizontalCentered(1);
                $objPHPExcel->addSheet($sheetTemp);
            }
        }
        $objPHPExcel->removeSheetByIndex(0);

        $obj3 = $this->generate_buyers_invoice_sheet($invoice_id, $invoice);
        $obj3->setActiveSheetIndex(0);
        $sheet3 = $obj3->getActiveSheet();
        $objPHPExcel->addExternalSheet($sheet3, 0);

        /**
         * * *************************************************************** */
        return $objPHPExcel;
    }

    public function generate_buyers_invoice_uk_sheet($invoice_id, $invoice) {

        $invoices = array();
        $i = 0;
        foreach ($invoice['containers'] as $container) {
            $invoices[$i]['master'] = $invoice['master'];
            $invoices[$i]['consignees'] = $invoice['consignees'];
            $invoices[$i]['containers'][] = $container;
            $invoices[$i]['container_from_city_id'] = $invoice['container_from_city_id'];
            $invoices[$i]['others'] = $invoice['others'];
            $i++;
        }

        $inputFile = FCPATH . 'downloads/invoices/templates/inv_temp_blank.xlsx';
        $reader = new PHPExcel_Reader_Excel2007();
        $objPHPExcel = $reader->load($inputFile);
        $objPHPExcel->setActiveSheetIndex(0);

        $container_alphabets = array("A", "B", "C", "D", "E", "F", "G", "H", "I",
            "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W",
            "X", "Y", "Z");
        $container_count = 0;
//        kas_pr($invoices);
//        die;
        $invoice_no = $invoice['master']->invoice_no;
        foreach ($invoices as $inv) {
            $container_letter = $container_alphabets[$container_count];
            $inv['master']->invoice_no = $invoice_no . "($container_letter)";
            $inv['invoice_year_details'] = $invoice['invoice_year_details'];
//            kas_pr($inv);
//            echo("<hr>Kashif<hr>");
            //
            $obj_temp = $this->generate_buyers_invoice_sheet($invoice_id, $inv);
            $obj_temp->setActiveSheetIndex(0);
            $sheet_temp = $obj_temp->getActiveSheet();
            $sheet_temp->setTitle("buyers_invoice_" . $container_letter);
            $objPHPExcel->addExternalSheet($sheet_temp);
            $container_count++;
        }
//        die;
        $objPHPExcel->removeSheetByIndex(0);

        return $objPHPExcel;
    }

    public function generate_buyers_invoice_mongolia_sheet($invoice_id, $invoice) {

        $result_data = array();
        $result_data['bl_no'] = $invoice['master']->bl_no;
        $result_data['booking_no'] = $invoice['master']->booking_no;
        $result_data['invoice_no'] = $invoice['master']->invoice_no;

        $invoice_year_id = $invoice['invoice_year_details']->invoice_year_id;

        $buyers_consignee_office_name = $invoice['consignees']['buyers_consignee']->office_name;
        $buyers_consignee_office_address = str_replace("\m", "\n", $invoice['consignees']['buyers_consignee']->office_address);

        $weekly_name = "";
        if (!empty($invoice['master']->weekly_name)) {
            $weekly_name = $invoice['master']->weekly_name;
        } else {
            $weekly_name = "n/a";
        }


        $from_address_B8 = "";
        if ($invoice['master']->from_city_id == 255) {
            $from_address_B8 = "HAKATA, JAPAN";
        } else if ($invoice['master']->from_city_id == 308) {
            $from_address_B8 = "YOKOHAMA VIA TOMAKOMAI, JAPAN";
        } else {
            //20 - Kenya
            //212 - Namibia
            //10 - Tanzania
            //279 - Uganda
            if ($invoice['master']->to_country_id == 20 || $invoice['master']->to_country_id == 212 || $invoice['master']->to_country_id == 10 || $invoice['master']->to_country_id == 279) {
                $invoice['master']->place_of_receipt = str_ireplace(", CY", "", $invoice['master']->place_of_receipt);
                $from_address_B8 .= $invoice['master']->place_of_receipt . ", " . $invoice['master']->from_country_name;
            } else {
                $from_address_B8 .= $invoice['master']->from_port_name . ", " . $invoice['master']->from_country_name;
            }
        }

        $inputFile = FCPATH . 'downloads/invoices/templates/inv_temp_buyers_invoice_mongolia.xlsx';

        $reader = new PHPExcel_Reader_Excel2007();
        $objPHPExcel = $reader->load($inputFile);
        /**
         * * ******************************************** */
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->setActiveSheetIndex(0);
        $sheet3 = $objPHPExcel->getActiveSheet();

        $sheet3->setCellValue('J4', date_to_invoice($invoice['master']->invoice_date));
        $sheet3->setCellValue('I5', "    NO. " . $invoice['master']->invoice_no);
        $sheet3->setCellValue('B6', $invoice['master']->invoice_type_name);
        $sheet3->setCellValue('B7', "\"" . $invoice['master']->vessel_name . "\"  VOY. " . $invoice['master']->voyage_no);

        $sheet3->setCellValue('H6', $weekly_name);
        $sheet3->setCellValue('J7', date_to_invoice($invoice['master']->shipment_date));

        $sheet3->setCellValue('B8', $from_address_B8);

        $i8 = "MONGOLIA VIA XINGANG, CHINA";

        $sheet3->setCellValue('I8', $i8);

        $sheet3->setCellValue('B9', $buyers_consignee_office_name);
        $sheet3->setCellValue('B10', $buyers_consignee_office_address);
        $sheet3->setCellValue('B12', $invoice['master']->payment_type);
        $sheet3->setCellValue('D12', "B/L NO " . $invoice['master']->bl_no);
        $sheet3->setCellValue('I12', "BOOKING NO. " . $invoice['master']->booking_no);
        $sheet3->setCellValue('A16', $invoice['master']->marks);
        $sheet3->setCellValue('B15', $invoice['master']->invoice_type_name);
//        $sheet3->setCellValue('I15', $invoice['master']->info);
        $sheet3->setCellValue('K15', "FOB JPY");

        $no_of_lines = array();
        $line_no = 16;
        $row = 17;
        $total_cars = 0;
        $total_m3 = 0;
        $total_weight = 0;
        $invoice_total_amount = 0;
        $pageBreaks = array();

        foreach ($invoice['containers'] as $container) {
            $container_total_m3 = 0;

            $sheet3->insertNewRowBefore($row, 1);
            $sheet3->setCellValue('B' . $row, "CONTAINER NO:  " . $container['container_no']);
//            $sheet3->getStyle('B' . $row)->getAlignment()->setWrapText(FALSE);
//            $sheet3->getStyle('B' . $row)->getAlignment()->setShrinkToFit(TRUE);
            $row++;
            $line_no++;

            $no_of_lines[] = array(
                'key' => "containers_" . $container['container_no'],
                'value' => $line_no);

            $sheet3->insertNewRowBefore($row, 1);
            $sheet3->setCellValue('B' . $row, "SEAL NO: " . $container['seal_no']);
            $row++;
            $line_no++;

            $no_of_lines[] = array(
                'key' => "seal_" . $container['seal_no'],
                'value' => $line_no);


            foreach ($container['details']['cars'] as $car) {
                $sheet3->insertNewRowBefore($row, 1);
                $line_no++;
                $start = $row;
                $sheet3->getRowDimension($row)->setRowHeight(30);
//                $sheet3->setCellValue('B' . $row, "$car->model_name ($car->maker_name):\r$car->chassis_no");
                $sheet3->setCellValue('B' . $row, "$car->model_name ($car->maker_name): $car->chassis_no\r$car->auction_company_name#$car->exhibition_number");
                $sheet3->getStyle('B' . $row)->getAlignment()->setWrapText(TRUE);

                $sheet3->setCellValue('C' . $row, "$car->registration_year");
                $sheet3->setCellValue('D' . $row, "$car->color_name\r$car->body_type_name");
                $sheet3->getStyle('D' . $row)->getAlignment()->setWrapText(TRUE);
                $sheet3->setCellValue('E' . $row, $car->dimension_l);
                $sheet3->setCellValue('F' . $row, $car->dimension_w);
                $sheet3->setCellValue('G' . $row, $car->dimension_h);
//                $m3 = ($car->dimension_l * $car->dimension_w * $car->dimension_h) / 1000;
                $m3 = ($car->dimension_l * $car->dimension_w * $car->dimension_h) / 1000000;
                $m3 = round($m3, 3);
                $sheet3->setCellValue('H' . $row, $m3);
                $sheet3->setCellValue('I' . $row, $car->weight);
                $sheet3->setCellValue('J' . $row, $car->engine_size);
//                $sheet3->setCellValue('K' . $row, "¥" . number_format($car->amount));
                $sheet3->setCellValue('K' . $row, "¥" . number_format($car->total_cost_without_bid_fee));
                $sheet3->getStyle('K' . $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

                $total_cars++;
                $total_m3 += $m3;
                $container_total_m3 += $m3;
                $total_weight += $car->weight;
//                $invoice_total_amount += $car->amount;
                $invoice_total_amount += $car->total_cost_without_bid_fee;

//                $sheet3->getStyle('A' . $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
//                $sheet3->getStyle('C' . $row)->getAlignment()->setWrapText(FALSE);
//                $sheet3->getStyle('C' . $row)->getAlignment()->setShrinkToFit(TRUE);


                $row++;

                $no_of_lines[] = array(
                    'key' => "car_" . $car->car_id,
                    'value' => $line_no);
            }

            $sheet3->insertNewRowBefore($row, 1);
            $line_no++;
            $sheet3->setCellValue('H' . $row, $container_total_m3);
            $row++;

            /*
              $i = 1;
              foreach ($container['details']['others'] as $other) {

              $sheet3->insertNewRowBefore($row, 1);
              $line_no++;

              $sheet3->setCellValue('A' . $row, round($other->weight) . "KGS");
              $sheet3->getStyle('A' . $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

              $sheet3->setCellValue('C' . $row, "$other->description");

              $merge_cells_range[] = 'C' . $row . ':D' . $row;

              $sheet3->setCellValue('E' . $row, "$other->quantity $other->quantity_unit");
              $sheet3->setCellValue('F' . $row, "¥" . number_format($other->unit_price));
              $other_total = $other->quantity * $other->unit_price;
              $sheet3->setCellValue('G' . $row, "¥" . number_format($other_total));
              $row++;

              if (!$other->commercial) {

              $sheet3->insertNewRowBefore($row, 1);
              $line_no++;
              $sheet3->setCellValue('E' . $row, "(NO COMMERICAL VALUE)");
              $sheet3->getStyle('E' . $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
              $merge_cells_range[] = 'E' . $row . ':G' . $row;

              $row++;
              }
              $no_of_lines[] = array(
              'key' => "other_" . $i,
              'value' => $line_no);
              $i++;
              }
             */
        }

        $sheet3->removeRow($row);
        $sheet3->removeRow($row);

        /*
          $forwarding_charges_text = "";
          foreach ($invoice['others'] as $inv_other) {
          if (stristr($inv_other->description, "forwarding")) {
          $fwd_charges = number_format($inv_other->unit_price);

          switch ($invoice_year_id) {
          case 1:
          $forwarding_charges_text = "(JPY$fwd_charges PER UNIT)";
          break;
          case 2:
          $forwarding_charges_text = "(JPY$fwd_charges PER CONTAINER)";
          break;
          }

          $inv_amount = 0;
          $inv_amount = $inv_other->quantity * $inv_other->unit_price;
          $invoice_total_amount += $inv_amount;
          $sheet3->setCellValue('G' . $row, $inv_other->description . "\r" . $forwarding_charges_text);
          $sheet3->setCellValue('K' . $row, $inv_amount);
          }
          }
         */
        $row += 2;

        $sheet3->setCellValue('B' . $row, $total_cars . " UNITS");
        $sheet3->setCellValue('H' . $row, $total_m3);
        $sheet3->setCellValue('I' . $row, $total_weight);
        $sheet3->setCellValue('K' . $row, $invoice_total_amount);
        /*
          $sheet3->insertNewRowBefore($row, 1);
          $line_no++;
          $row++;
          $i = 1;
         */
        /*
          foreach ($invoice['others'] as $inv_other) {

          if ($inv_other->amount > 0) {

          if (!stristr($inv_other->description, "freight")) {
          $sheet3->insertNewRowBefore($row, 1);
          $line_no++;


          if (stristr($inv_other->description, "forwarding")) {
          $sheet3->insertNewRowBefore($row, 1);
          $line_no++;
          $sheet3->setCellValue('C' . $row, $forwarding_charges_text);
          $merge_cells_range[] = 'C' . $row . ':D' . $row;
          $row++;
          }

          $no_of_lines[] = array(
          'key' => "inv_other_" . $i,
          'value' => ($line_no - 1));
          }

          $no_of_lines[] = array(
          'key' => "inv_other_" . $i,
          'value' => ($line_no - 1));
          }
          }

         */

        /*
          $line_no += 13;
          $no_of_lines[] = array(
          'key' => "inv_other_" . $i,
          'value' => ($line_no - 1));
          $invoice_sheet_last_row = $row;

         */
        $pageBreaks = array();
        $page_lines = 51;

        foreach ($no_of_lines as $index => $node) {
            //            $modulus = $node['value'] % 52;
            //            echo("$node[key] - $node[value] - $modulus<br>");
            //            echo("$node[key] - $node[value]<br>");

            if (isset($no_of_lines[$index + 1])) {
                //                echo("Page Lines - $page_lines<br>"
                //                        . "Current - $node[value] <br>"
                //                        . " Next " . $no_of_lines[$index + 1]['value'] . "<br>");

                if ($node['value'] <= $page_lines && ($no_of_lines[$index + 1]['value'] > $page_lines)) {
                    if (strstr($node['key'], "car")) {
                        $pageBreaks[] = "A" . ($node['value']);
                        //                        echo("TRUE = Car - A" . $node['value'] . "<br>");
                    } else if (strstr($node['key'], "containers")) {
                        if ($no_of_lines[$index + 1]['value'] > $page_lines) {
                            $pageBreaks[] = "A" . ($no_of_lines[$index - 1]['value']);
                            //                            echo("Container-1 - A" . ($no_of_lines[$index - 1]['value']) . "<br>");
                        } else {
                            $pageBreaks[] = "A" . ($node['value']);
                            //                            echo("TRUE = Container-2 - A" . $node['value'] . "<br>");
                        }
                    } else {
                        $pageBreaks[] = "A" . ($node['value'] - 1);
                        //                        echo("TRUE = Other - A" . $node['value'] . "<br>");
                    }
                    $page_lines += 51;
                }
                //                echo("<hr>");
            }
        }

//        foreach ($merge_cells_range as $cell_range) {
//            $sheet3->mergeCells($cell_range);
//        }

        $sheet3->getPageSetup()->setRowsToRepeatAtTop(array(14, 14));

        /**
         * * *************************************************************** */
        $sheet3->getPageSetup()->setFitToWidth(1);
        $sheet3->getPageSetup()->setFitToHeight(0);
        $sheet3->getPageSetup()->setHorizontalCentered(1);
        /*
          foreach ($pageBreaks as $pb) {
          $sheet3->setBreak($pb, PHPExcel_Worksheet::BREAK_ROW);
          }
         *
         */
//        die;
        return $objPHPExcel;
    }

    public function generate_attachement_sheet($invoice_id, $invoice) {

        $result_data = array();
        $result_data['bl_no'] = $invoice['master']->bl_no;
        $result_data['booking_no'] = $invoice['master']->booking_no;
        $result_data['invoice_no'] = $invoice['master']->invoice_no;

        $inputFile = FCPATH . 'downloads/invoices/templates/inv_temp_attachement.xlsx';


        $reader = new PHPExcel_Reader_Excel2007();
        $objPHPExcel = $reader->load($inputFile);

        /**
         * * ******************************************** */
        $merge_cells_range = array();

        $objPHPExcel->setActiveSheetIndex(0);
        $sheet4 = $objPHPExcel->getActiveSheet();

//        $sheet4->setCellValue('A1', "BOOKING NO. " . $invoice['master']->booking_no);
//        $sheet4->setCellValue('D1', "INVOICE NO. " . $invoice['master']->invoice_no);
        $sheet4->setCellValue('C1', $invoice['master']->booking_no);
        $sheet4->setCellValue('E1', $invoice['master']->invoice_no);

        $row = 2;

        $containers_count = 0;
        $pageBreaks = array();

        foreach ($invoice['containers'] as $container) {

            $sheet4->setCellValue('A' . $row, $container['container_no']);
            $row++;
            foreach ($container['details']['cars'] as $car) {
//                $sheet4->insertNewRowBefore($row, 1);
                $start = $row;
                $sheet4->setCellValue('B' . $row, $car->item_no);
                $sheet4->getStyle('B' . $row)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
                $sheet4->setCellValue('C' . $row, "USED $car->maker_name");
                $sheet4->getStyle('C' . $row)->getAlignment()->setWrapText(FALSE);
                $sheet4->getStyle('C' . $row)->getAlignment()->setShrinkToFit(TRUE);

                $grade_model = $car->model_name;
                if ($car->imported) {
                    $grade_model .= " $car->grade_name";
                }
                $sheet4->setCellValue('D' . $row, "$grade_model");
                $sheet4->getStyle('D' . $row)->getAlignment()->setWrapText(FALSE);
                $sheet4->getStyle('D' . $row)->getAlignment()->setShrinkToFit(TRUE);

                $sheet4->setCellValue('E' . $row, "1 UNIT");
                $sheet4->getStyle('E' . $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                $sheet4->getStyle('E' . $row)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
                $row++;

//                $sheet4->insertNewRowBefore($row, 1);
                $sheet4->setCellValue('C' . $row, "CHASSIS NO.");
                $sheet4->setCellValue('D' . $row, "$car->chassis_no");
                $row++;

//                $sheet4->insertNewRowBefore($row, 1);
                $sheet4->setCellValue('C' . $row, "ENGINE NO.");
                $sheet4->setCellValue('D' . $row, "$car->engine_no ($car->fuel_name/$car->engine_size CC)");
                $sheet4->getStyle('D' . $row)->getAlignment()->setShrinkToFit(TRUE);
                $row++;

                //Namibia - 212
                // kenya - 20
                $o_e_c = array(20, 212);
                if (in_array($car->to_country_id, $o_e_c)) {
//                    $sheet4->insertNewRowBefore($row, 1);
                    $sheet4->setCellValue('C' . $row, "ENGINE NO.");
                    $sheet4->setCellValue('D' . $row, $car->engine_code);
                    $row++;
                }

                //imported and uae
                if ($car->imported && $car->to_country_id == 17) {
//                    $sheet4->insertNewRowBefore($row, 1);
                    $sheet4->setCellValue('C' . $row, "STEERING");
                    $sheet4->setCellValue('D' . $row, $car->steering_name);
                    $row++;

//                    $sheet4->insertNewRowBefore($row, 1);
                    $sheet4->setCellValue('C' . $row, "COUNTRY");
                    $sheet4->setCellValue('D' . $row, $car->maker_country);
                    $row++;

//                    $sheet4->insertNewRowBefore($row, 1);
                    $sheet4->setCellValue('C' . $row, "TYPE");
                    $sheet4->setCellValue('D' . $row, $car->body_type_name);
                    $row++;
                }
//                $sheet4->insertNewRowBefore($row, 1);


                $reg_year_month_label = "REG YEAR";
                $reg_year_month_value = $car->registration_year;
//                $sheet4->insertNewRowBefore($row, 1);
                //Kenya
                if ($car->to_country_id == 20) {
                    $reg_year_month_label .= "/MONTH";
                    $reg_year_month_value .= "/" . $car->registration_month;
                }
                $reg_year_month_label .= ":";

                $sheet4->setCellValue('C' . $row, $reg_year_month_label);
                $sheet4->setCellValue('D' . $row, "$reg_year_month_value");
                $sheet4->getStyle('D' . $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

                $row++;

                //Tanzania
                if ($car->to_country_id == 10) {
//                    $sheet4->insertNewRowBefore($row, 1);
                    $sheet4->setCellValue('C' . $row, "MFG YEAR:");
                    $sheet4->setCellValue('D' . $row, "$car->manufacture_year");
                    $row++;
                }

//                $sheet4->insertNewRowBefore($row, 1);
                $sheet4->setCellValue('C' . $row, "COLOR");
                $sheet4->setCellValue('D' . $row, "$car->color_name");

                $merge_cells_range[] = 'A' . $start . ':A' . $row;
                $merge_cells_range[] = 'B' . $start . ':B' . $row;
                $merge_cells_range[] = 'E' . $start . ':E' . $row;

                $row++;
            }

            foreach ($container['details']['others'] as $other) {

//                $sheet4->insertNewRowBefore($row, 1);
                $sheet4->setCellValue('C' . $row, "$other->description");
                $merge_cells_range[] = 'C' . $row . ':D' . $row;

                $sheet4->setCellValue('E' . $row, "$other->quantity $other->quantity_unit");
                $row++;
            }

            $containers_count++;
            if ($containers_count % 2 == 0) {
                $pageBreaks[] = 'A' . ($row - 1);
            }
        }

//        if (isset($invoice['others'])) {
        foreach ($invoice['others'] as $inv_other) {
            $desc = strtolower($inv_other->description);
            if ($desc != "inspection fee" && !stristr($desc, "forwarding") && !stristr($desc, "freight")) {
//                $sheet4->insertNewRowBefore($row, 1);

                $sheet4->setCellValue('C' . $row, "$inv_other->description");
                $merge_cells_range[] = 'C' . $row . ':D' . $row;

                $sheet4->setCellValue('E' . $row, "$inv_other->quantity $inv_other->quantity_unit");
                $row++;
            }
        }
//        }
//        foreach ($merge_cells_range as $cell_range) {
//            $sheet4->mergeCells($cell_range);
//        }

        /**
         * **************************************************************** */
//        $sheet4->getPageSetup()->setFitToWidth(1);
//        $sheet4->getPageSetup()->setFitToHeight(0);
//        $sheet4->getPageSetup()->setHorizontalCentered(1);
//        $sheet4->getPageSetup()->setFitToPage(TRUE);
//
        $sheet4->getPageSetup()->setFitToWidth(1);
        $sheet4->getPageSetup()->setFitToHeight(0);
        $sheet4->getPageSetup()->setHorizontalCentered(1);

        $i = 0;
        $last_break = '';
//        echo($containers_count);
        foreach ($pageBreaks as $pb) {
            $i += 2;

            if ($i < $containers_count) {
//                echo($i . ' - ' . $containers_count . '-' . $pb .  '<br>');
                $sheet4->setBreak($pb, PHPExcel_Worksheet::BREAK_ROW);
            } else {
                $last_break = $pb;
            }
        }
        return $objPHPExcel;
    }

    public function generate_attachement_uk_sheet($invoice_id, $invoice) {

        $invoices = array();
        $i = 0;
        foreach ($invoice['containers'] as $container) {
            $invoices[$i]['master'] = $invoice['master'];
            $invoices[$i]['consignees'] = $invoice['consignees'];
            $invoices[$i]['containers'][] = $container;
            $invoices[$i]['container_from_city_id'] = $invoice['container_from_city_id'];
            $invoices[$i]['others'] = $invoice['others'];
            $i++;
        }

        $inputFile = FCPATH . 'downloads/invoices/templates/inv_temp_blank.xlsx';
        $reader = new PHPExcel_Reader_Excel2007();
        $objPHPExcel = $reader->load($inputFile);
        $objPHPExcel->setActiveSheetIndex(0);

        $container_alphabets = array("A", "B", "C", "D", "E", "F", "G", "H", "I",
            "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W",
            "X", "Y", "Z");
        $container_count = 0;
//        kas_pr($invoices);
//        die;
        $invoice_no = $invoice['invoice_no_original'];
        foreach ($invoices as $inv) {
            $container_letter = $container_alphabets[$container_count];
            $inv['master']->invoice_no = $invoice_no . "($container_letter)";
//            kas_pr($inv);
//            echo("<hr>Kashif<hr>");
            //
            $obj_temp = $this->generate_attachement_sheet($invoice_id, $inv);
            $obj_temp->setActiveSheetIndex(0);
            $sheet_temp = $obj_temp->getActiveSheet();
            $sheet_temp->setTitle("attachement_" . $container_letter);
            $objPHPExcel->addExternalSheet($sheet_temp);
            $container_count++;
        }
//        die;
        $objPHPExcel->removeSheetByIndex(0);

        return $objPHPExcel;
    }

    public function generate_packing_list_sheet($invoice_id, $invoice) {

        $invoice['master']->invoice_no = $invoice['invoice_no_original'];
        $result_data = array();
        $result_data['bl_no'] = $invoice['master']->bl_no;
        $result_data['booking_no'] = $invoice['master']->booking_no;
        $result_data['invoice_no'] = $invoice['master']->invoice_no;

        if (stristr($invoice['master']->to_port_name, "Durban")) {
            $invoice['master']->to_port_name = "DURBAN";
        }

//        if (!empty($invoice['master']->weekly_name)) {
//            $weekly_name = substr($invoice['master']->weekly_name, 7);
//        } else {
//            $weekly_name = "n/a";
//        }

        $weekly_name = "";
        if (!empty($invoice['master']->weekly_name)) {
            $pakistan_ids = array(282, 317);

            if (!in_array($invoice['master']->to_country_id, $pakistan_ids)) {
//            if ($invoice['master']->to_country_id != 282) {
                $weekly_name = substr($invoice['master']->weekly_name, 7);
            } else {
                $weekly_name = $invoice['master']->weekly_name;
            }
        } else {
            $weekly_name = "n/a";
        }

        $packing_consignee_office_name = $invoice['consignees']['buyers_consignee']->office_name;
        $packing_consignee_office_address = str_replace("\m", "\n", $invoice['consignees']['buyers_consignee']->office_address);

        //FAKOUKA
        if ($invoice['master']->from_city_id == 255) {
            $from_address_C8 = "HAKATA, JAPAN";
        } else {
            //20 - Kenya
            //212 - Namibia
            //10 - Tanzania
            if ($invoice['master']->to_country_id == 20 || $invoice['master']->to_country_id == 212 || $invoice['master']->to_country_id == 10 || $invoice['master']->to_country_id == 279) {
                $invoice['master']->place_of_receipt = str_ireplace(", CY", "", $invoice['master']->place_of_receipt);
                $from_address_C8 = $invoice['master']->place_of_receipt . ", " . $invoice['master']->from_country_name;
            } else {
                $from_address_C8 = $invoice['master']->from_city_name . ", " . $invoice['master']->from_country_name;
            }
        }

        $inputFile = FCPATH . 'downloads/invoices/templates/inv_temp_packing_list.xlsx';

        $reader = new PHPExcel_Reader_Excel2007();
        $reader = PHPExcel_IOFactory::createReaderForFile($inputFile);
        $objPHPExcel = $reader->load($inputFile);
        /**
         * * ******************************************** */
        $objPHPExcel->setActiveSheetIndex(0);

        $all_containers_total_units_count = 0;
        $all_containers_total_pcs_count = 0;
        $all_containers_total_g_weight = 0;

        $total_containers = 0;
        $total_cars = 0;
        $invoice_total_weight = 0;
        $invoice_total_units_count = 0;
        $invoice_total_pcs_count = 0;
        $invoice_str_quantity = "";

        $row = 28;
        foreach ($invoice['containers'] as $container) {
            $container_units_count = 0;
            $container_pcs_count = 0;
            $container_g_weight = 0;
            $str_quanity = "";

            $total_containers++;

            foreach ($container['details']['cars'] as $car) {
                $total_cars++;
                $container_units_count++;
                $container_g_weight += $car->weight;
            }
            foreach ($container['details']['others'] as $other) {
                if ($other->quantity_unit == "UNITS") {
                    $container_units_count += $other->quantity;
                } else if ($other->quantity_unit == "PCS") {
                    $container_pcs_count += $other->quantity;
                }
                $container_g_weight += $other->weight;
            }
            $all_containers_total_units_count += $container_units_count;
            $all_containers_total_pcs_count += $container_pcs_count;


            if ($container_units_count && $container_pcs_count) {
                $str_quanity = "$container_units_count UNITS & $container_pcs_count PCS";
            } else if ($container_units_count && !$container_pcs_count) {
                $str_quanity = "$container_units_count UNITS";
            } else if (!$container_units_count && $container_pcs_count) {
                $str_quanity = "$container_pcs_count PCS";
            }

            $all_containers_total_g_weight += $container_g_weight;
            $row++;
        }
        $row += 2;

        $str_quanity_total = "";
        if ($all_containers_total_units_count && $all_containers_total_pcs_count) {
            $str_quanity_total = "$all_containers_total_units_count UNITS & $all_containers_total_pcs_count PCS";
        } else if ($all_containers_total_units_count && !$all_containers_total_pcs_count) {
            $str_quanity_total = "$all_containers_total_units_count UNITS";
        } else if (!$all_containers_total_units_count && $all_containers_total_pcs_count) {
            $str_quanity_total = "$all_containers_total_pcs_count PCS";
        }

        $invoice_total_weight += $all_containers_total_g_weight;

        $invoice_total_units_count += $all_containers_total_units_count;
        $invoice_total_pcs_count += $all_containers_total_pcs_count;

        foreach ($invoice['others'] as $inv_other) {

            if (!stristr($inv_other->description, "Forwarding") && !stristr($inv_other->description, "inspection") && !stristr($inv_other->description, "freight")) {
                $invoice_total_weight += $inv_other->weight;
                if ($inv_other->quantity_unit == "UNITS") {
                    $invoice_total_units_count += $inv_other->quantity;
                } else if ($inv_other->quantity_unit == "PCS") {
                    $invoice_total_pcs_count += $inv_other->quantity;
                }
            }
        }

        if ($invoice_total_units_count && $invoice_total_pcs_count) {
            $invoice_str_quantity = "$invoice_total_units_count UNITS & $invoice_total_pcs_count PCS";
        } else if ($invoice_total_units_count && !$invoice_total_pcs_count) {
            $invoice_str_quantity = "$invoice_total_units_count UNITS";
        } else if (!$invoice_total_units_count && $invoice_total_pcs_count) {
            $invoice_str_quantity = "$invoice_total_pcs_count PCS";
        }

        /**
         * * *********************************************************** */
        $merge_cells_range = array();

        $sheet5 = $objPHPExcel->getActiveSheet();
        $sheet5->setCellValue('F4', "  " . date_to_invoice($invoice['master']->invoice_date));
        $sheet5->setCellValue('F5', " " . $invoice['master']->invoice_no);
        $sheet5->setCellValue('C6', $invoice['master']->invoice_type_name);
        $sheet5->setCellValue('C7', "\"" . $invoice['master']->vessel_name . "\"  VOY. " . $invoice['master']->voyage_no);

        $sheet5->setCellValue('E6', $weekly_name);


        $sheet5->setCellValue('F7', date_to_invoice($invoice['master']->shipment_date));


        $sheet5->setCellValue('C8', $from_address_C8);

        if ($invoice['master']->to_country_id == 359) {
            $f8 = "SWAZILAND VIA DURBAN";
        } else if ($invoice['master']->to_country_id == 353) {
            $f8 = "LESOTHO VIA DURBAN";
        } else if ($invoice['master']->to_country_id == 396) {
            $f8 = "MONGOLIA VIA XINGANG, CHINA";
        } else {
            if ($invoice['master']->to_country_id == 317) {
                $invoice['master']->to_country_name = "PAKISTAN";
            }

            $f8 = $invoice['master']->to_port_name . ", " . $invoice['master']->to_country_name;
        }
        $sheet5->setCellValue('F8', $f8);

        $sheet5->setCellValue('C9', $packing_consignee_office_name);
        $sheet5->setCellValue('C10', $packing_consignee_office_address);
        $sheet5->setCellValue('A12', "Payment " . $invoice['master']->payment_type);
        $sheet5->setCellValue('D12', "B/L NO " . $invoice['master']->bl_no);
        $sheet5->setCellValue('F12', "BOOKING NO. " . $invoice['master']->booking_no);
        $sheet5->setCellValue('A14', $invoice['master']->marks);
        $sheet5->setCellValue('C14', $invoice['master']->invoice_type_name);
        $sheet5->setCellValue('F14', $invoice['master']->info);
        $sheet5->setCellValue('A21', number_format($invoice_total_weight) . " KGS");
        $sheet5->setCellValue('C21', "40' x $total_containers CONTAINERS");
        $sheet5->setCellValue('E21', $invoice_str_quantity);
        $sheet5->getStyle('E22')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

        $row = 16;
        $cars = 0;
        $pageBreaks = array();
        foreach ($invoice['containers'] as $container) {

            $sheet5->insertNewRowBefore($row, 1);
            $sheet5->setCellValue('A' . $row, $container['container_no']);
            $row++;
            foreach ($container['details']['cars'] as $car) {
                $cars++;
                $sheet5->insertNewRowBefore($row, 1);
                $start = $row;
                $sheet5->setCellValue('A' . $row, number_format(round($car->weight)) . " KGS");
                $sheet5->getStyle('A' . $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $sheet5->setCellValue('B' . $row, $car->item_no);
                $sheet5->setCellValue('C' . $row, "USED $car->maker_name");
                $sheet5->getStyle('C' . $row)->getAlignment()->setWrapText(FALSE);
                $sheet5->getStyle('C' . $row)->getAlignment()->setShrinkToFit(TRUE);
                $grade_model = $car->model_name;
                if ($car->imported) {
                    $grade_model .= " $car->grade_name";
                }

                $sheet5->setCellValue('D' . $row, "$grade_model");
                $sheet5->getStyle('D' . $row)->getAlignment()->setWrapText(FALSE);
                $sheet5->getStyle('D' . $row)->getAlignment()->setShrinkToFit(TRUE);

                $sheet5->getStyle('D' . $row)->getAlignment()->setWrapText(FALSE);
                $sheet5->getStyle('D' . $row)->getAlignment()->setShrinkToFit(TRUE);
                $sheet5->setCellValue('E' . $row, "1 UNIT");
                $sheet5->getStyle('E' . $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $row++;

                $sheet5->insertNewRowBefore($row, 1);
                $sheet5->setCellValue('C' . $row, "CHASSIS NO.");
                $sheet5->setCellValue('D' . $row, $car->chassis_no);
                $row++;

                $sheet5->insertNewRowBefore($row, 1);
                $sheet5->setCellValue('C' . $row, "ENGINE NO.");
                $sheet5->setCellValue('D' . $row, $car->engine_no . " (" . $car->fuel_name . "/" . $car->engine_size . " CC)");
                $sheet5->getStyle('D' . $row)->getAlignment()->setShrinkToFit(TRUE);
                $row++;

                //Namibia
                if ($car->to_country_id == 212) {
                    $sheet5->insertNewRowBefore($row, 1);
                    $sheet5->setCellValue('C' . $row, "ENGINE NO.");
                    $sheet5->setCellValue('D' . $row, $car->engine_code);
                    $row++;
                }

                $sheet5->insertNewRowBefore($row, 1);

                $reg_year_month_label = "REG YEAR";
                $reg_year_month_value = $car->registration_year;

                //Kenya
                if ($car->to_country_id == 20) {
                    $reg_year_month_label .= "/MONTH";
                    $reg_year_month_value .= "/" . $car->registration_month;
                }
                $sheet5->setCellValue('C' . $row, $reg_year_month_label);
                $sheet5->setCellValue('D' . $row, $reg_year_month_value);
                $row++;

                //Tanzania
                if ($car->to_country_id == 10) {
                    $sheet5->insertNewRowBefore($row, 1);
                    $sheet5->setCellValue('C' . $row, "MFG YEAR:");
                    $sheet5->setCellValue('D' . $row, "$car->manufacture_year");
                    $row++;
                }

                $sheet5->insertNewRowBefore($row, 1);
                $sheet5->setCellValue('C' . $row, "COLOR");
                $sheet5->setCellValue('D' . $row, $car->color_name);

                $merge_cells_range[] = 'A' . $start . ':A' . $row;
                $merge_cells_range[] = 'B' . $start . ':B' . $row;
                $merge_cells_range[] = 'E' . $start . ':E' . $row;
                $merge_cells_range[] = 'F' . $start . ':F' . $row;
                $merge_cells_range[] = 'G' . $start . ':G' . $row;

                if ($cars == 5 || ($cars > 5 && (($cars - 5) % 7 == 0))) {
                    $pageBreaks[] = 'A' . $row;
                }
                $row++;
            }

            foreach ($container['details']['others'] as $other) {

                $sheet5->insertNewRowBefore($row, 1);

                $sheet5->setCellValue('A' . $row, round($other->weight) . "KGS");
                $sheet5->getStyle('A' . $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

                $sheet5->setCellValue('C' . $row, "$other->description");
                $merge_cells_range[] = 'C' . $row . ':D' . $row;

                $sheet5->setCellValue('E' . $row, "$other->quantity $other->quantity_unit");
                $row++;
            }
        }
        $sheet5->insertNewRowBefore($row, 1);
        $row++;

        foreach ($invoice['others'] as $inv_other) {

            if (!stristr($inv_other->description, "Forwarding") && !stristr($inv_other->description, "inspection") && !stristr($inv_other->description, "freight")) {
                $sheet5->insertNewRowBefore($row, 1);
                $inv_other->weight = round($inv_other->weight);


                if ($inv_other->weight) {
                    $sheet5->setCellValue('A' . $row, round($inv_other->weight) . "KGS");
                    $sheet5->getStyle('A' . $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                }
                $sheet5->setCellValue('E' . $row, "$inv_other->quantity $inv_other->quantity_unit");

                $sheet5->setCellValue('C' . $row, "$inv_other->description");
                $merge_cells_range[] = 'C' . $row . ':D' . $row;

                $row++;
            }
        }
        $invoice_sheet_last_row = $row;
        foreach ($merge_cells_range as $cell_range) {
            $sheet5->mergeCells($cell_range);
        }

        $sheet5->getPageSetup()->setRowsToRepeatAtTop(array(13, 13));

        /**
         * * *************************************************************** */
        $sheet5->getPageSetup()->setFitToWidth(1);
        $sheet5->getPageSetup()->setFitToHeight(0);
        $sheet5->getPageSetup()->setHorizontalCentered(TRUE);
        foreach ($pageBreaks as $pb) {
            $sheet5->setBreak($pb, PHPExcel_Worksheet::BREAK_ROW);
        }
        return $objPHPExcel;
    }

    public function generate_shipping_instructions_sheet($invoice_id, $invoice) {
        $result_data = array();
        $result_data['bl_no'] = $invoice['master']->bl_no;
        $result_data['booking_no'] = $invoice['master']->booking_no;
        $result_data['invoice_no'] = $invoice['master']->invoice_no;

        if ($invoice['master']->from_city_name == "KOBE YARD A") {
            $invoice['master']->from_city_name = "KOBE";
        }
        if (stristr($invoice['master']->to_port_name, "Durban")) {
            $invoice['master']->to_port_name = "DURBAN";
        }

        //I29
        //255 - FAKOUKA
        if ($invoice['master']->from_city_id == 255) {
            $port_of_loading = "HAKATA, JAPAN";
        }
        //308 - YOKOHAMA VIA TOMAKOMAI
        else if ($invoice['master']->from_city_id == 308) {
            $port_of_loading = "YOKOHAMA, JAPAN";
        } else {
            //20 - Kenya
            if ($invoice['master']->to_country_id == 20) {
                $port_of_loading = str_ireplace(", CY", "", $invoice['master']->place_of_receipt);
                $port_of_loading .= ", " . $invoice['master']->from_country_name;
            } else {
                //212 - Namibia
                //10 - Tanzania
                //279 - Uganda
                if ($invoice['master']->to_country_id == 212 || $invoice['master']->to_country_id == 10 || $invoice['master']->to_country_id == 279) {
                    $invoice['master']->place_of_receipt = str_ireplace(", CY", "", $invoice['master']->place_of_receipt);
                    $port_of_loading = $invoice['master']->place_of_receipt . ", " . $invoice['master']->from_country_name;
                } else {
                    $port_of_loading = $invoice['master']->from_city_name . ", " . $invoice['master']->from_country_name;
                }
            }
        }

        //20 - Kenya
        if ($invoice['master']->to_country_id == 20) {
            //B31
            $port_of_discharge = $invoice['master']->to_port_name . ", " . $invoice['master']->to_country_name;
            //I31
            $place_of_delivery = $invoice['master']->place_of_delievery . ", " . $invoice['master']->to_country_name;
        } else if ($invoice['master']->to_country_id == 267) {
            //Thailand
            $port_of_discharge = $invoice['master']->place_of_delievery;
            $place_of_delivery = $invoice['master']->place_of_delievery . ", CY";
        } else if ($invoice['master']->to_country_id == 359) {
            //swaziland
            $port_of_discharge = $invoice['master']->to_port_name;
            $place_of_delivery = 'MATSAPHA, SWAZILAND';
        } else {
            $port_of_discharge = $invoice['master']->to_port_name;
            $place_of_delivery = $invoice['master']->place_of_delievery;
        }

        $shipping_consignee_office_address = str_replace("\m", "\n", $invoice['consignees']['shipping_consignee']->office_address);
        $shipping_consignee_office_name = $invoice['consignees']['shipping_consignee']->office_name;

        //Zambia
        $notify_party = "";
//        if ($invoice['master']->to_country_id == 12) {
//            $notify_party .= "1)SAME AS CONSIGNEE\n2)";
//        }

        $notify_party .= str_replace("\m", "\n", $invoice['master']->notify_party);

        $inputFile = FCPATH . 'downloads/invoices/templates/inv_temp_shipping_instructions.xlsx';

        $reader = new PHPExcel_Reader_Excel2007();
        $objPHPExcel = $reader->load($inputFile);

        /**
         * ********************************************
         */
        $objPHPExcel->setActiveSheetIndex(0);

        $sheet1 = $objPHPExcel->getActiveSheet();

        $sheet1->setCellValue('B9', $invoice['master']->invoice_office_name_eng . "\nNO.9-49 ONOHAMA-CHO CHUO-KU KOBE JAPAN");

        $sheet1->setCellValue('B14', $shipping_consignee_office_name . "\n" . $shipping_consignee_office_address);
        $sheet1->setCellValue('B20', $notify_party);
        $sheet1->setCellValue('B25', $invoice['master']->pre_carriage);
        $sheet1->setCellValue('B29', $invoice['master']->vessel_name);

        $sheet1->setCellValue('B38', $invoice['master']->marks_and_numbers);

        $sheet1->setCellValue('B55', $invoice['master']->route_detail);
        $sheet1->setCellValue('B57', $invoice['master']->other_information);

        $sheet1->setCellValue('H29', $invoice['master']->voyage_no);

        $sheet1->setCellValue('I25', $invoice['master']->place_of_receipt);

        $sheet1->setCellValue('I29', $port_of_loading);
        $sheet1->setCellValue('B31', $port_of_discharge);
        $sheet1->setCellValue('I31', $place_of_delivery);

        $sheet1->setCellValue('K38', $invoice['master']->invoice_type_name);
        $sheet1->setCellValue('K56', $invoice['master']->invoice_no);

        $sheet1->setCellValue('M27', $invoice['master']->cut_date);
        $sheet1->setCellValue('M29', $invoice['master']->shipment_date);
        $sheet1->setCellValue('N3', $invoice['master']->invoice_date);
        $sheet1->setCellValue('N5', $invoice['master']->person_incharge);

        $sheet1->setCellValue('N8', $invoice['master']->booking_no);
        $sheet1->setCellValue('N10', $invoice['master']->shipping_company_name);
        $sheet1->setCellValue('N12', $invoice['master']->service);
        $sheet1->setCellValue('N14', $invoice['master']->bl_to_be_released_at);
        $sheet1->setCellValue('N16', $invoice['master']->ocean_freight_payable_at);
        $sheet1->setCellValue('N18', $invoice['master']->freight);
        $sheet1->setCellValue('N21', $invoice['master']->invoice_no);

        $all_containers_total_units_count = 0;
        $all_containers_total_pcs_count = 0;
        $all_containers_total_amount = 0;
        $all_containers_total_g_weight = 0;
        $all_containers_no_commercial_total_amount = 0;
        $all_containers_tera_weight = 0;

        $total_containers = 0;
        $total_cars = 0;
        $invoice_total_amount = 0;
        $invoice_total_weight = 0;
        $invoice_total_units_count = 0;
        $invoice_total_pcs_count = 0;
        $invoice_no_commercial_total_amount = 0;
        $invoice_str_quantity = "";
        $str_invoice_no_commercial = "";

        $row = 42;
        foreach ($invoice['containers'] as $container) {
            $container_units_count = 0;
            $container_pcs_count = 0;
            $container_g_weight = 0;
            $str_quanity = "";

            $total_containers++;

            foreach ($container['details']['cars'] as $car) {
                $total_cars++;
                $container_units_count++;
                $container_g_weight += $car->weight;
                $all_containers_total_amount += $car->amount;
            }
            foreach ($container['details']['others'] as $other) {
                $other_amount = $other->unit_price * $other->quantity;
                if ($other->commercial) {
                    $all_containers_total_amount += $other_amount;
                } else {
                    $all_containers_no_commercial_total_amount += $other_amount;
                }
                if ($other->quantity_unit == "UNITS") {
                    $container_units_count += $other->quantity;
                } else if ($other->quantity_unit == "PCS") {
                    $container_pcs_count += $other->quantity;
                }
                $container_g_weight += $other->weight;
            }
            $all_containers_total_units_count += $container_units_count;
            $all_containers_total_pcs_count += $container_pcs_count;


            if ($container_units_count && $container_pcs_count) {
                $str_quanity = "$container_units_count UNITS & $container_pcs_count PCS";
            } else if ($container_units_count && !$container_pcs_count) {
                $str_quanity = "$container_units_count UNITS";
            } else if (!$container_units_count && $container_pcs_count) {
                $str_quanity = "$container_pcs_count PCS";
            }

            $all_containers_total_g_weight += $container_g_weight;
            $sheet1->setCellValue('C' . $row, $container['container_no'])
                    ->setCellValue('H' . $row, $container['seal_no'])
                    ->setCellValue('J' . $row, $container_g_weight)
                    ->setCellValue('K' . $row, "KGS")
                    ->setCellValue('L' . $row, $container['total_weight'])
                    ->setCellValue('N' . $row, "KGS")
                    ->setCellValue('O' . $row, $str_quanity)
                    ->setCellValue('P' . $row, $container['leading_character']);
            $all_containers_tera_weight += $container['total_weight'];
            $row++;
        }
        $row += 2;

        $str_quanity_total = "";
        if ($all_containers_total_units_count && $all_containers_total_pcs_count) {
            $str_quanity_total = "$all_containers_total_units_count UNITS & $all_containers_total_pcs_count PCS";
        } else if ($all_containers_total_units_count && !$all_containers_total_pcs_count) {
            $str_quanity_total = "$all_containers_total_units_count UNITS";
        } else if (!$all_containers_total_units_count && $all_containers_total_pcs_count) {
            $str_quanity_total = "$all_containers_total_pcs_count PCS";
        }

        $sheet1->setCellValue("H38", "$total_containers CONTAINERS");
        $sheet1->setCellValue("H39", $str_quanity_total);

        $row++;
        $all_containers_tera_weight = number_format($all_containers_tera_weight);
        $all_containers_total_g_weight = number_format($all_containers_total_g_weight);
        $sheet1->setCellValue("J54", $all_containers_total_g_weight);
        $sheet1->setCellValue("L54", "$all_containers_tera_weight");

        $sheet1->setCellValue("O54", $str_quanity_total);

        /**
         * *********************************************************************
         */
        $sheet1->getPageSetup()->setFitToWidth(1);
        $sheet1->getPageSetup()->setFitToHeight(0);
        $sheet1->getPageSetup()->setHorizontalCentered(1);

        return $objPHPExcel;
    }

    //Karachi Shipping Instructions
    public function generate_shipping_instructions_kch_sheet($invoice_id, $invoice) {
        $result_data = array();
        $result_data['bl_no'] = $invoice['master']->bl_no;
        $result_data['booking_no'] = $invoice['master']->booking_no;
        $result_data['invoice_no'] = $invoice['master']->invoice_no;

        if ($invoice['master']->from_city_name == "KOBE YARD A") {
            $invoice['master']->from_city_name = "KOBE";
        }
        if (stristr($invoice['master']->to_port_name, "Durban")) {
            $invoice['master']->to_port_name = "DURBAN";
        }

        $shipping_consignee_office_address = str_replace("\m", "\n", $invoice['consignees']['shipping_consignee']->office_address);
        $shipping_consignee_office_name = $invoice['consignees']['shipping_consignee']->office_name;

        $notify_party = str_replace("\m", "\n", $invoice['master']->notify_party);


        //I29
        //255 - FAKOUKA
        if ($invoice['master']->from_city_id == 255) {
            $port_of_loading = "HAKATA, JAPAN";
        }
        //308 - YOKOHAMA VIA TOMAKOMAI
        else if ($invoice['master']->from_city_id == 308) {
            $port_of_loading = "YOKOHAMA, JAPAN";
        } else {
            $port_of_loading = $invoice['master']->from_city_name . ", " . $invoice['master']->from_country_name;
        }

        $port_of_discharge = $invoice['master']->to_port_name;
        $place_of_delivery = $invoice['master']->place_of_delievery;


        $inputFile = FCPATH . 'downloads/invoices/templates/inv_temp_shipping_instructions_kch.xlsx';

        $reader = new PHPExcel_Reader_Excel2007();
        $objPHPExcel = $reader->load($inputFile);

        /**
         * ********************************************
         */
//$invoice_id, $invoice

        $objPHPExcel->setActiveSheetIndex(0);
        $sheet1 = $objPHPExcel->getActiveSheet();

        $sheet1->setCellValue('B9', $invoice['master']->invoice_office_name_eng . "\nNO.9-49 ONOHAMA-CHO CHUO-KU KOBE JAPAN");

        $sheet1->setCellValue('B14', $shipping_consignee_office_name . "\n" . $shipping_consignee_office_address);
        $sheet1->setCellValue('B20', $notify_party);
        $sheet1->setCellValue('B25', $invoice['master']->pre_carriage);
        $sheet1->setCellValue('B29', $invoice['master']->vessel_name);

        $sheet1->setCellValue('B38', $invoice['master']->marks_and_numbers);
//        $sheet1->setCellValue('B55', $invoice['master']->route_detail);
        $sheet1->setCellValue('B51', "SURRENDERED B/L");

        $sheet1->setCellValue('B53', $invoice['master']->other_information);

        $sheet1->setCellValue('H29', $invoice['master']->voyage_no);

        $sheet1->setCellValue('I25', $invoice['master']->place_of_receipt);


        $sheet1->setCellValue('K52', $invoice['master']->invoice_no);

        $sheet1->setCellValue('M27', $invoice['master']->cut_date);
        $sheet1->setCellValue('M29', $invoice['master']->shipment_date);
        $sheet1->setCellValue('N3', $invoice['master']->invoice_date);
        $sheet1->setCellValue('N5', $invoice['master']->person_incharge);

        $sheet1->setCellValue('N8', $invoice['master']->booking_no);
        $sheet1->setCellValue('N10', $invoice['master']->shipping_company_name);
        $sheet1->setCellValue('N12', $invoice['master']->service);
        $sheet1->setCellValue('N14', $invoice['master']->bl_to_be_released_at);
        $sheet1->setCellValue('N16', $invoice['master']->ocean_freight_payable_at);
        $sheet1->setCellValue('N18', $invoice['master']->freight);
        $sheet1->setCellValue('N21', $invoice['master']->invoice_no);

        $sheet1->setCellValue('I29', $port_of_loading);
        $sheet1->setCellValue('B31', $port_of_discharge);
        $sheet1->setCellValue('I31', $place_of_delivery);


        $total_containers = count($invoice['containers']);

        $sheet1->setCellValue("H38", "$total_containers CONTAINERS");

        $count = 1;
        foreach ($invoice['containers'] as $container) {
            foreach ($container['details']['cars'] as $car) {
                $sheetTemp = $sheet1->copy();
                $sheetTemp->setTitle("shipping_" . $count++);
                $sheetTemp->setCellValue('K38', "1 UNIT OF USED $car->maker_name $car->model_name");
                $sheetTemp->setCellValue('N39', $car->chassis_no);
                $sheetTemp->setCellValue('N40', "$car->engine_no  ($car->fuel_name/$car->engine_size" . "CC)");
                $sheetTemp->setCellValue('C44', $container['container_no'])
                        ->setCellValue('H44', $container['seal_no'])
                        ->setCellValue('J44', $car->weight)
                        ->setCellValue('L44', $container['total_weight'])
                        ->setCellValue('P44', $car->item_no);
                $sheetTemp->getPageSetup()->setFitToWidth(1);
                $sheetTemp->getPageSetup()->setFitToHeight(0);
                $sheetTemp->getPageSetup()->setHorizontalCentered(1);
                $objPHPExcel->addSheet($sheetTemp);
            }
        }
        $objPHPExcel->removeSheetByIndex(0);

        $obj1a = $this->generate_shipping_instructions_sheet($invoice_id, $invoice);
        $obj1a->setActiveSheetIndex(0);
        $sheet1a = $obj1a->getActiveSheet();
        $sheet1a->setTitle('shipping_instructions_full');
        $objPHPExcel->addExternalSheet($sheet1a, 0);

        $objPHPExcel->setActiveSheetIndex(0);
        /**
         * *********************************************************************
         */
        return $objPHPExcel;
    }

    public function generate_shipping_instructions_uk_sheet($invoice_id, $invoice) {
        $result_data = array();
        $result_data['bl_no'] = $invoice['master']->bl_no;
        $result_data['booking_no'] = $invoice['master']->booking_no;
        $result_data['invoice_no'] = $invoice['master']->invoice_no;

        if ($invoice['master']->from_city_name == "KOBE YARD A") {
            $invoice['master']->from_city_name = "KOBE";
        }
        if (stristr($invoice['master']->to_port_name, "Durban")) {
            $invoice['master']->to_port_name = "DURBAN";
        }

        //I29
        //255 - FAKOUKA
        if ($invoice['master']->from_city_id == 255) {
            $port_of_loading = "HAKATA, JAPAN";
        }
        //308 - YOKOHAMA VIA TOMAKOMAI
        else if ($invoice['master']->from_city_id == 308) {
            $port_of_loading = "YOKOHAMA, JAPAN";
        } else {
            //20 - Kenya
            if ($invoice['master']->to_country_id == 20) {
                $port_of_loading = str_ireplace(", CY", "", $invoice['master']->place_of_receipt);
                $port_of_loading .= ", " . $invoice['master']->from_country_name;
            } else {
                //212 - Namibia
                //10 - Tanzania
                //279 - Uganda
                if ($invoice['master']->to_country_id == 212 || $invoice['master']->to_country_id == 10 || $invoice['master']->to_country_id == 279) {
                    $invoice['master']->place_of_receipt = str_ireplace(", CY", "", $invoice['master']->place_of_receipt);
                    $port_of_loading = $invoice['master']->place_of_receipt . ", " . $invoice['master']->from_country_name;
                } else {
                    $port_of_loading = $invoice['master']->from_city_name . ", " . $invoice['master']->from_country_name;
                }
            }
        }

        //20 - Kenya
        if ($invoice['master']->to_country_id == 20) {
            //B31
            $port_of_discharge = $invoice['master']->to_port_name . ", " . $invoice['master']->to_country_name;
            //I31
            $place_of_delivery = $invoice['master']->place_of_delievery . ", " . $invoice['master']->to_country_name;
        } else if ($invoice['master']->to_country_id == 267) {
            //Thailand
            $port_of_discharge = $invoice['master']->place_of_delievery;
            $place_of_delivery = $invoice['master']->place_of_delievery . ", CY";
        } else {
            $port_of_discharge = $invoice['master']->to_port_name;
            $place_of_delivery = $invoice['master']->place_of_delievery;
        }

        $shipping_consignee_office_address = str_replace("\m", "\n", $invoice['consignees']['shipping_consignee']->office_address);
        $shipping_consignee_office_name = $invoice['consignees']['shipping_consignee']->office_name;

        //Zambia
        $notify_party = "";
        if ($invoice['master']->to_country_id == 12) {
            $notify_party .= "1)SAME AS CONSIGNEE\n2)";
        }

        $notify_party .= str_replace("\m", "\n", $invoice['master']->notify_party);

        $inputFile = FCPATH . 'downloads/invoices/templates/inv_temp_shipping_instructions.xlsx';

        $reader = new PHPExcel_Reader_Excel2007();
        $objPHPExcel = $reader->load($inputFile);

        /**
         * ********************************************
         */
        $objPHPExcel->setActiveSheetIndex(0);

        $sheet1 = $objPHPExcel->getActiveSheet();

        $sheet1->setCellValue('B9', $invoice['master']->invoice_office_name_eng . "\nNO.9-49 ONOHAMA-CHO CHUO-KU KOBE JAPAN");

        $sheet1->setCellValue('B14', $shipping_consignee_office_name . "\n" . $shipping_consignee_office_address);
        $sheet1->setCellValue('B20', $notify_party);
        $sheet1->setCellValue('B25', $invoice['master']->pre_carriage);
        $sheet1->setCellValue('B29', $invoice['master']->vessel_name);

        $sheet1->setCellValue('B38', $invoice['master']->marks_and_numbers);

        $sheet1->setCellValue('B55', $invoice['master']->route_detail);
        $sheet1->setCellValue('B57', $invoice['master']->other_information);

        $sheet1->setCellValue('H29', $invoice['master']->voyage_no);

        $sheet1->setCellValue('I25', $invoice['master']->place_of_receipt);

        $sheet1->setCellValue('I29', $port_of_loading);
        $sheet1->setCellValue('B31', $port_of_discharge);
        $sheet1->setCellValue('I31', $place_of_delivery);

        $sheet1->setCellValue('K38', $invoice['master']->invoice_type_name);
        $sheet1->setCellValue('K56', $invoice['master']->invoice_no);

        $sheet1->setCellValue('M27', $invoice['master']->cut_date);
        $sheet1->setCellValue('M29', $invoice['master']->shipment_date);
        $sheet1->setCellValue('N3', $invoice['master']->invoice_date);
        $sheet1->setCellValue('N5', $invoice['master']->person_incharge);

        $sheet1->setCellValue('N8', $invoice['master']->booking_no);
        $sheet1->setCellValue('N10', $invoice['master']->shipping_company_name);
        $sheet1->setCellValue('N12', $invoice['master']->service);
        $sheet1->setCellValue('N14', $invoice['master']->bl_to_be_released_at);
        $sheet1->setCellValue('N16', $invoice['master']->ocean_freight_payable_at);
        $sheet1->setCellValue('N18', $invoice['master']->freight);
        $sheet1->setCellValue('N21', $invoice['master']->invoice_no);


        $obj_combined = $this->generate_shipping_instructions_sheet($invoice_id, $invoice);
        $obj_combined->setActiveSheetIndex(0);
        $sheet_combined = $obj_combined->getActiveSheet();
        $sheet_combined->setTitle("shipping_instructions_");
        $objPHPExcel->addExternalSheet($sheet_combined);

        $container_alphabets = array("A", "B", "C", "D", "E", "F", "G", "H", "I",
            "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W",
            "X", "Y", "Z");
        $container_count = 0;
        foreach ($invoice['containers'] as $container) {
            $sheetTemp = $sheet1->copy();
            $container_letter = $container_alphabets[$container_count];
//            $sheetTemp->setTitle("shipping_instructions_" . $container['container_packing_id']);
            $sheetTemp->setTitle("shipping_instructions_" . $container_letter);
            $sheetTemp->setCellValue('N21', $invoice['master']->invoice_no . "($container_letter)");
            $row = 42;
            $all_containers_total_units_count = 0;
            $all_containers_total_pcs_count = 0;
            $all_containers_total_amount = 0;
            $all_containers_total_g_weight = 0;
            $all_containers_no_commercial_total_amount = 0;
            $all_containers_tera_weight = 0;

            $total_containers = 0;
            $total_cars = 0;
            $invoice_total_amount = 0;
            $invoice_total_weight = 0;
            $invoice_total_units_count = 0;
            $invoice_total_pcs_count = 0;
            $invoice_no_commercial_total_amount = 0;
            $invoice_str_quantity = "";
            $str_invoice_no_commercial = "";
            $container_units_count = 0;
            $container_pcs_count = 0;
            $container_g_weight = 0;
            $str_quanity = "";

            $total_containers++;

            foreach ($container['details']['cars'] as $car) {
                $total_cars++;
                $container_units_count++;
                $container_g_weight += $car->weight;
                $all_containers_total_amount += $car->amount;
            }
            foreach ($container['details']['others'] as $other) {
                $other_amount = $other->unit_price * $other->quantity;
                if ($other->commercial) {
                    $all_containers_total_amount += $other_amount;
                } else {
                    $all_containers_no_commercial_total_amount += $other_amount;
                }
                if ($other->quantity_unit == "UNITS") {
                    $container_units_count += $other->quantity;
                } else if ($other->quantity_unit == "PCS") {
                    $container_pcs_count += $other->quantity;
                }
                $container_g_weight += $other->weight;
            }
            $all_containers_total_units_count += $container_units_count;
            $all_containers_total_pcs_count += $container_pcs_count;


            if ($container_units_count && $container_pcs_count) {
                $str_quanity = "$container_units_count UNITS & $container_pcs_count PCS";
            } else if ($container_units_count && !$container_pcs_count) {
                $str_quanity = "$container_units_count UNITS";
            } else if (!$container_units_count && $container_pcs_count) {
                $str_quanity = "$container_pcs_count PCS";
            }

            $all_containers_total_g_weight += $container_g_weight;
            $sheetTemp->setCellValue('C' . $row, $container['container_no'])
                    ->setCellValue('H' . $row, $container['seal_no'])
                    ->setCellValue('J' . $row, $container_g_weight)
                    ->setCellValue('K' . $row, "KGS")
                    ->setCellValue('L' . $row, $container['total_weight'])
                    ->setCellValue('N' . $row, "KGS")
                    ->setCellValue('O' . $row, $str_quanity)
                    ->setCellValue('P' . $row, $container['leading_character']);
            $all_containers_tera_weight += $container['total_weight'];
            $row++;

            $str_quanity_total = "";
            if ($all_containers_total_units_count && $all_containers_total_pcs_count) {
                $str_quanity_total = "$all_containers_total_units_count UNITS & $all_containers_total_pcs_count PCS";
            } else if ($all_containers_total_units_count && !$all_containers_total_pcs_count) {
                $str_quanity_total = "$all_containers_total_units_count UNITS";
            } else if (!$all_containers_total_units_count && $all_containers_total_pcs_count) {
                $str_quanity_total = "$all_containers_total_pcs_count PCS";
            }

            $sheetTemp->setCellValue("H38", "$total_containers CONTAINERS");
            $sheetTemp->setCellValue("H39", $str_quanity_total);

            $row++;
            $all_containers_tera_weight = number_format($all_containers_tera_weight);
            $all_containers_total_g_weight = number_format($all_containers_total_g_weight);
            $sheetTemp->setCellValue("J53", $all_containers_total_g_weight);
            $sheetTemp->setCellValue("L53", "$all_containers_tera_weight");

            $sheetTemp->setCellValue("O53", $str_quanity_total);
            $sheetTemp->getPageSetup()->setFitToWidth(1);
            $sheetTemp->getPageSetup()->setFitToHeight(0);
            $sheetTemp->getPageSetup()->setHorizontalCentered(1);
            $objPHPExcel->addSheet($sheetTemp);
            $container_count++;
        }
        $objPHPExcel->removeSheetByIndex(0);


        /**
         * *********************************************************************
         */
        return $objPHPExcel;
    }

    public function generate_car_details_sheet($invoice_id, $invoice) {

        $result_data = array();
        $result_data['bl_no'] = $invoice['master']->bl_no;
        $result_data['booking_no'] = $invoice['master']->booking_no;
        $result_data['invoice_no'] = $invoice['master']->invoice_no;

        $inputFile = FCPATH . 'downloads/invoices/templates/inv_temp_car_details.xlsx';

        $reader = new PHPExcel_Reader_Excel2007();
        $objPHPExcel = $reader->load($inputFile);

        /**
         * * ******************************************* */
        $merge_cells_range = array();

        $objPHPExcel->setActiveSheetIndex(0);
        $sheet6 = $objPHPExcel->getActiveSheet();
        $row = 2;
        foreach ($invoice['containers'] as $container) {
            $i = 1;
            $start = 0;
            $item_no = "";
            $first = TRUE;
            foreach ($container['details']['cars'] as $car) {
                $item_no = $car->item_no;
                $leading_char = substr($item_no, 0, 1);

                if ($first) {
                    $sheet6->setCellValue('A' . $row, $leading_char);
                    $sheet6->getStyle('A' . $row)->getFont()->setBold(true);
                    $sheet6->getStyle('A' . $row)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $start = $row;
                    $first = FALSE;
                }
                $sheet6->setCellValue('B' . $row, $i++);
                $sheet6->setCellValue('C' . $row, $car->weight);
                $sheet6->setCellValue('D' . $row, $car->maker_name);
                $sheet6->setCellValue('E' . $row, $car->registration_year);
                $sheet6->setCellValue('F' . $row, $car->chassis_no);
                $sheet6->setCellValue('G' . $row, $car->engine_no);
                $sheet6->setCellValue('H' . $row, $car->fuel_name);
                $sheet6->setCellValue('I' . $row, $car->engine_size);
                $sheet6->setCellValue('J' . $row, $car->model_name);
                $sheet6->setCellValue('K' . $row, "");
                $sheet6->setCellValue('L' . $row, $car->engine_code);
                $sheet6->setCellValue('M' . $row, $car->color_name);
                $sheet6->setCellValue('N' . $row, $car->amount);
                $row++;
            }
            $merge_cells_range[] = 'A' . $start . ':A' . ($row - 1);
        }

        foreach ($merge_cells_range as $cell_range) {
            $sheet6->mergeCells($cell_range);
        }

        /**
         *  ************************************************************* */
        $sheet6->getPageSetup()->setFitToWidth(1);
        $sheet6->getPageSetup()->setFitToHeight(0);
        $sheet6->getPageSetup()->setHorizontalCentered(1);

        return $objPHPExcel;
    }

    public function generate_ship_details_sheet($invoice_id, $invoice) {

        $invoice['master']->invoice_no = $invoice['invoice_no_original'];
        $result_data = array();
        $result_data['bl_no'] = $invoice['master']->bl_no;
        $result_data['booking_no'] = $invoice['master']->booking_no;
        $result_data['invoice_no'] = $invoice['master']->invoice_no;

        $inputFile = FCPATH . 'downloads/invoices/templates/inv_temp_ship_details.xlsx';

        $reader = new PHPExcel_Reader_Excel2007();
        $objPHPExcel = $reader->load($inputFile);

        /**
         * * ******************************************* */
        $total_containers = count($invoice['containers']);
        $objPHPExcel->setActiveSheetIndex(0);
        $sheet7 = $objPHPExcel->getActiveSheet();
        $sheet7->setCellValue('B2', $invoice['master']->invoice_date);
        $sheet7->setCellValue('B3', $invoice['master']->person_incharge);
        $sheet7->setCellValue('B4', $invoice['master']->booking_no);
        $sheet7->setCellValue('B5', $invoice['master']->shipping_company_name);
        $sheet7->setCellValue('B6', $invoice['master']->invoice_no);
        $sheet7->setCellValue('B7', $invoice['master']->vessel_name);
        $sheet7->setCellValue('B8', $invoice['master']->voyage_no);
        $sheet7->setCellValue('B9', $invoice['master']->shipment_date);
        $sheet7->setCellValue('B10', $total_containers);

        $row = 2;
        foreach ($invoice['containers'] as $container) {
            $sheet7->setCellValue('C' . $row, $container['container_no']);
            $sheet7->setCellValue('D' . $row, $container['seal_no']);
            $sheet7->setCellValue('E' . $row, $container['total_weight']);
            $sheet7->setCellValue('F' . $row, $container['total_units']);
            $sheet7->setCellValue('G' . $row, $container['leading_character']);
            $sheet7->setCellValue('H' . $row, $container['container_date']);
            $row++;
        }

        /**
         * ***************************************************************** */
        $sheet7->getPageSetup()->setFitToWidth(1);
        $sheet7->getPageSetup()->setFitToHeight(0);
        $sheet7->getPageSetup()->setHorizontalCentered(1);

        return $objPHPExcel;
    }

    public function generate_container_maps_sheet($invoice_id, $invoice) {
        $invoice['master']->invoice_no = $invoice['invoice_no_original'];
        $result_data = array();
        $result_data['bl_no'] = $invoice['master']->bl_no;
        $result_data['booking_no'] = $invoice['master']->booking_no;
        $result_data['invoice_no'] = $invoice['master']->invoice_no;

//        echo($invoice['master']->invoice_address_jpn);
//        die;
        $inputFile = FCPATH . 'downloads/invoices/templates/inv_temp_container_map.xlsx';

        $reader = new PHPExcel_Reader_Excel2007();
        $objPHPExcel = $reader->load($inputFile);
        /**
         * *******************************************************************
         * Container Maps
         */
        $objPHPExcel->setActiveSheetIndex(0);
        $sheetMapTemp = $objPHPExcel->getActiveSheet();
        $objPHPExcel->setActiveSheetIndex(1);
        $sheetMapTemp_7 = $objPHPExcel->getActiveSheet();
        $objPHPExcel->setActiveSheetIndex(0);

        $sheet_count = 1;
        foreach ($invoice['containers'] as $container) {
            $total_cars = count($container['details']['cars']);
            if ($total_cars <= 6) {
                $sheetMap = $sheetMapTemp->copy();
            } else {
                $sheetMap = $sheetMapTemp_7->copy();
            }
            $sheetMap->setTitle("container_$sheet_count");
            $sheet_count++;
            $sheetMap->setCellValue('D3', $container['container_date']);
            $sheetMap->setCellValue('E1', $invoice['master']->invoice_no);
//            $sheetMap->setCellValue('D5', $invoice['master']->invoice_address_jpn);
            $sheetMap->setCellValue('D5', $container['from_city_jpn_address']);

            $sheetMap->setCellValue('D9', $invoice['master']->invoice_type_name);

            $sheetMap->setCellValue('D14', $container['container_no']);
            $sheetMap->setCellValue('D16', $container['seal_no']);
            $sheetMap->setCellValue('D18', "\"" . $invoice['master']->vessel_name . "\" VOY. " . $invoice['master']->voyage_no);

            $sheetMap->setCellValue('E15', $container['total_weight'] . " KGS)");

            if ($invoice['master']->invoice_type == 1) {
                $cars_count = 0;
                $total_cars_weight = 0;
                foreach ($container['details']['cars'] as $car) {
                    $total_cars_weight += $car->weight;
                    $cars_count++;
                    switch ($car->position_no) {
                        case 1:
                            $sheetMap->setCellValue('N3', "$car->item_no\n$car->chassis_no");
                            break;
                        case 2:
                            $sheetMap->setCellValue('N9', "$car->item_no\n$car->chassis_no");
                            break;
                        case 3:
                            $sheetMap->setCellValue('K3', "$car->item_no\n$car->chassis_no");
                            break;
                        case 4:
                            $sheetMap->setCellValue('K9', "$car->item_no\n$car->chassis_no");
                            break;
                        case 5:
                            $sheetMap->setCellValue('H3', "$car->item_no\n$car->chassis_no");
                            $sheetMap->setCellValue('H16', "$car->item_no\n$car->chassis_no");
                            break;
                        case 6:
                            $sheetMap->setCellValue('H9', "$car->item_no\n$car->chassis_no");
                            if ($total_cars <= 6) {
                                $sheetMap->setCellValue('H20', "$car->item_no\n$car->chassis_no");
                            } else {
                                $sheetMap->setCellValue('J20', "$car->item_no\n$car->chassis_no");
                            }
                            break;
                        case 7:
                            $sheetMap->setCellValue('H20', "$car->item_no\n$car->chassis_no");
                            break;
                    }
                }

                foreach ($container['details']['others'] as $other) {
                    switch ($other->position_no) {
                        case 1:
                            $sheetMap->setCellValue('N8', "$other->description");
                            break;
                        case 2:
                            $sheetMap->setCellValue('N14', "$other->description");
                            break;
                        case 3:
                            $sheetMap->setCellValue('K8', "$other->description");
                            break;
                        case 4:
                            $sheetMap->setCellValue('K14', "$other->description");
                            break;
                        case 5:
                            $sheetMap->setCellValue('H8', "$other->description");
                            break;
                        case 6:
                            $sheetMap->setCellValue('H14', "$other->description");
                            break;
                    }
                }

                $sheetMap->setCellValue('E19', "$total_cars_weight KGS)");
                $sheetMap->setCellValue('D11', "$cars_count UNITS");

                $sheetMap->getPageSetup()->setFitToWidth(1);
                $sheetMap->getPageSetup()->setFitToHeight(0);
                $sheetMap->getPageSetup()->setHorizontalCentered(1);

                $objPHPExcel->addSheet($sheetMap);
            }
        }
        $objPHPExcel->removeSheetByIndex(0);
        $objPHPExcel->removeSheetByIndex(0);

        /**
         * **************************************************************** */
        $objPHPExcel->setActiveSheetIndex(0);

        return $objPHPExcel;
    }

    public function generate_container_sheet($container_packing_ids) {

        $inputFile = FCPATH . 'downloads/containers/templates/container_temp.xlsx';

        $reader = new PHPExcel_Reader_Excel2007();
        $objPHPExcel = $reader->load($inputFile);

        $objPHPExcel->setActiveSheetIndex(0);
        $sheetTemp = $objPHPExcel->getActiveSheet();

        foreach ($container_packing_ids as $cpid) {
            $container = $this->container->get_container($cpid);
            $container_details = $this->container->get_container_details($cpid);
            $container_no = $this->container->get_container_no($cpid);
            $cars_sort = $container_details['cars_sort'];

//            display_admin_debug($container, "array");
            $sheet2 = $sheetTemp->copy();

            $sheet2->setTitle($container_no);

            $sheet2->setCellValue('A1', strtoupper($container->discharge_city));
            $sheet2->setCellValue('B1', $container->leading_charater);
            $sheet2->setCellValue('C2', strtoupper($container->place_of_destination));
            $sheet2->setCellValue('C3', "DATE  " . date('d-M-y', strtotime($container->container_date)));
            $sheet2->setCellValue('B4', $container->container_no);
            $sheet2->setCellValue('B5', $container->seal_no);
            $sheet2->setCellValue('B6', $container->total_weight);
            $sheet2->setCellValue('B9', $container->total_units);

            $sheet2->setCellValue('A39', $container->person_incharge);

            $stamp_cell_row = 39;
            $final_amount = 0;
            $i = 0;
            foreach ($container_details['cars'] as $car) {
                $i++;

                $other_amount = $car->repair_cost + $car->other_cost;
                $amount = $car->vehicle_fee + $car->vehicle_price;
                $total_amount = $other_amount + $amount;
                $final_amount += $car->amount;
                if (!empty($car->repair_cost) || !empty($car->other_cost)) {
                    $finalize_total = '¥' . number_format($amount) . '+ ¥' . number_format($other_amount) . ' = ¥' . number_format($total_amount);
                } else {
                    $finalize_total = '¥' . number_format($amount);
                }

                switch ($car->position_no) {
                    case 1:
                        $sheet2->setCellValue('C12', $car->item_no);
                        break;

                    case 2:
                        $sheet2->setCellValue('C13', $car->item_no);
                        break;

                    case 3:
                        $sheet2->setCellValue('B12', $car->item_no);
                        break;

                    case 4:
                        $sheet2->setCellValue('B13', $car->item_no);
                        break;

                    case 5:
                        $sheet2->setCellValue('A12', $car->item_no);
                        break;

                    case 6:
                        $sheet2->setCellValue('A13', $car->item_no);
                        break;
                }

                switch ($i) {
                    case 1:
                        $sheet2->setCellValue('A15', "$i $car->chassis_no");
                        $sheet2->setCellValue('B15', "$car->model_name");
                        $sheet2->setCellValue('C15', "$car->color_name");

                        $sheet2->setCellValue('A16', date('m/d', strtotime($car->salable_registered_day)) . '  ' . $car->auction_company_name . '  ' . $car->car_file_name);
                        $sheet2->setCellValue('B16', $finalize_total);
                        $c16 = $car->registration_year;
                        if ($car->to_country_id == 20) {
                            $c16 .= "/$car->registration_month";
                        }
                        $sheet2->setCellValue('C16', $c16);
//                        $sheet2->getStyle('C16')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

                        $sheet2->setCellValue('B17', $car->engine_code);
                        if ($car->imported && $car->to_country_id == 17) {
                            $sheet2->setCellValue('A17', "STEERING : " . strtoupper($car->steering_name));
                        }

                        break;

                    case 2:
                        $sheet2->setCellValue('A18', "$i $car->chassis_no");
                        $sheet2->setCellValue('B18', "$car->model_name");
                        $sheet2->setCellValue('C18', "$car->color_name");

                        $sheet2->setCellValue('A19', date('m/d', strtotime($car->salable_registered_day)) . '  ' . $car->auction_company_name . '  ' . $car->car_file_name);
                        $sheet2->setCellValue('B19', $finalize_total);
                        $c19 = $car->registration_year;
                        if ($car->to_country_id == 20) {
                            $c19 .= "/$car->registration_month";
                        }
                        $sheet2->setCellValue('C19', $c19);
//                        $sheet2->getStyle('C19')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

                        if ($car->imported && $car->to_country_id == 17) {
                            $sheet2->setCellValue('A20', "STEERING : " . strtoupper($car->steering_name));
                        }
                        $sheet2->setCellValue('B20', $car->engine_code);

                        break;

                    case 3:
                        $sheet2->setCellValue('A21', "$i $car->chassis_no");
                        $sheet2->setCellValue('B21', "$car->model_name");
                        $sheet2->setCellValue('C21', "$car->color_name");

                        $sheet2->setCellValue('A22', date('m/d', strtotime($car->salable_registered_day)) . '  ' . $car->auction_company_name . '  ' . $car->car_file_name);
                        $sheet2->setCellValue('B22', $finalize_total);
                        $c22 = $car->registration_year;
                        if ($car->to_country_id == 20) {
                            $c22 .= "/$car->registration_month";
                        }
                        $sheet2->setCellValue('C22', $c22);
//                        $sheet2->getStyle('C22')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);


                        if ($car->imported && $car->to_country_id == 17) {
                            $sheet2->setCellValue('A23', "STEERING : " . strtoupper($car->steering_name));
                        }
                        $sheet2->setCellValue('B23', $car->engine_code);

                        break;

                    case 4:
                        $sheet2->setCellValue('A24', "$i $car->chassis_no");
                        $sheet2->setCellValue('B24', "$car->model_name");
                        $sheet2->setCellValue('C24', "$car->color_name");

                        $sheet2->setCellValue('A25', date('m/d', strtotime($car->salable_registered_day)) . '  ' . $car->auction_company_name . '  ' . $car->car_file_name);
                        $sheet2->setCellValue('B25', $finalize_total);
                        $c25 = $car->registration_year;
                        if ($car->to_country_id == 20) {
                            $c25 .= "/$car->registration_month";
                        }
                        $sheet2->setCellValue('C25', $c25);
//                        $sheet2->getStyle('C25')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

                        if ($car->imported && $car->to_country_id == 17) {
                            $sheet2->setCellValue('A26', "STEERING : " . strtoupper($car->steering_name));
                        }
                        $sheet2->setCellValue('B26', $car->engine_code);

                        break;

                    case 5:
                        $sheet2->setCellValue('A27', "$i $car->chassis_no");
                        $sheet2->setCellValue('B27', "$car->model_name");
                        $sheet2->setCellValue('C27', "$car->color_name");

                        $sheet2->setCellValue('A28', date('m/d', strtotime($car->salable_registered_day)) . '  ' . $car->auction_company_name . '  ' . $car->car_file_name);
                        $sheet2->setCellValue('B28', $finalize_total);
                        $c28 = $car->registration_year;
                        if ($car->to_country_id == 20) {
                            $c28 .= "/$car->registration_month";
                        }
                        $sheet2->setCellValue('C28', $c28);
//                        $sheet2->getStyle('C28')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

                        if ($car->imported && $car->to_country_id == 17) {
                            $sheet2->setCellValue('A29', "STEERING : " . strtoupper($car->steering_name));
                        }
                        $sheet2->setCellValue('B29', $car->engine_code);

                        break;

                    case 6:
                        $sheet2->setCellValue('A30', "$i $car->chassis_no");
                        $sheet2->setCellValue('B30', "$car->model_name");
                        $sheet2->setCellValue('C30', "$car->color_name");

                        $sheet2->setCellValue('A31', date('m/d', strtotime($car->salable_registered_day)) . '  ' . $car->auction_company_name . '  ' . $car->car_file_name);
                        $sheet2->setCellValue('B31', $finalize_total);
                        $c31 = $car->registration_year;
                        if ($car->to_country_id == 31) {
                            $c31 .= "/$car->registration_month";
                        }
                        $sheet2->setCellValue('C31', $c31);
//                        $sheet2->getStyle('C31')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

                        if ($car->imported && $car->to_country_id == 17) {
                            $sheet2->setCellValue('A32', "STEERING : " . strtoupper($car->steering_name));
                        }
                        $sheet2->setCellValue('B32', $car->engine_code);

                        break;

                    case 7:
                        $sheet2->setCellValue('A33', "$i $car->chassis_no");
                        $sheet2->setCellValue('B33', "$car->model_name");
                        $sheet2->setCellValue('C33', "$car->color_name");

                        $sheet2->setCellValue('A34', date('m/d', strtotime($car->salable_registered_day)) . '  ' . $car->auction_company_name . '  ' . $car->car_file_name);
                        $sheet2->setCellValue('B34', $finalize_total);
                        $c34 = $car->registration_year;
                        if ($car->to_country_id == 31) {
                            $c34 .= "/$car->registration_month";
                        }
                        $sheet2->setCellValue('C34', $c34);
//                        $sheet2->getStyle('C31')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

                        if ($car->imported && $car->to_country_id == 17) {
                            $sheet2->setCellValue('A35', "STEERING : " . strtoupper($car->steering_name));
                        }
                        $sheet2->setCellValue('B35', $car->engine_code);

                        break;
                }
            }

            $sheet2->setCellValue('C39', '¥ ' . number_format($final_amount));


            switch ($i) {
                case 1:
                    $sheet2->removeRow(35);
                    $sheet2->removeRow(34);
                    $sheet2->removeRow(33);
                    $sheet2->removeRow(32);
                    $sheet2->removeRow(31);
                    $sheet2->removeRow(30);
                    $sheet2->removeRow(29);
                    $sheet2->removeRow(28);
                    $sheet2->removeRow(27);
                    $sheet2->removeRow(26);
                    $sheet2->removeRow(25);
                    $sheet2->removeRow(24);
                    $sheet2->removeRow(23);
                    $sheet2->removeRow(22);
                    $sheet2->removeRow(21);
                    $sheet2->removeRow(20);
                    $sheet2->removeRow(19);
                    $sheet2->removeRow(18);
                    $stamp_cell_row -= 18;
                    break;

                case 2:
                    $sheet2->removeRow(35);
                    $sheet2->removeRow(34);
                    $sheet2->removeRow(33);
                    $sheet2->removeRow(32);
                    $sheet2->removeRow(31);
                    $sheet2->removeRow(30);
                    $sheet2->removeRow(29);
                    $sheet2->removeRow(28);
                    $sheet2->removeRow(27);
                    $sheet2->removeRow(26);
                    $sheet2->removeRow(25);
                    $sheet2->removeRow(24);
                    $sheet2->removeRow(23);
                    $sheet2->removeRow(22);
                    $sheet2->removeRow(21);
                    $stamp_cell_row -= 15;
                    break;

                case 3:
                    $sheet2->removeRow(35);
                    $sheet2->removeRow(34);
                    $sheet2->removeRow(33);
                    $sheet2->removeRow(32);
                    $sheet2->removeRow(31);
                    $sheet2->removeRow(30);
                    $sheet2->removeRow(29);
                    $sheet2->removeRow(28);
                    $sheet2->removeRow(27);
                    $sheet2->removeRow(26);
                    $sheet2->removeRow(25);
                    $sheet2->removeRow(24);
                    $stamp_cell_row -= 12;
                    break;

                case 4:
                    $sheet2->removeRow(35);
                    $sheet2->removeRow(34);
                    $sheet2->removeRow(33);
                    $sheet2->removeRow(32);
                    $sheet2->removeRow(31);
                    $sheet2->removeRow(30);
                    $sheet2->removeRow(29);
                    $sheet2->removeRow(28);
                    $sheet2->removeRow(27);
                    $stamp_cell_row -= 9;
                    break;

                case 5:
                    $sheet2->removeRow(35);
                    $sheet2->removeRow(34);
                    $sheet2->removeRow(33);
                    $sheet2->removeRow(32);
                    $sheet2->removeRow(31);
                    $sheet2->removeRow(30);
                    $stamp_cell_row -= 6;
                    break;
                case 6:
                    $sheet2->removeRow(35);
                    $sheet2->removeRow(34);
                    $sheet2->removeRow(33);
                    $stamp_cell_row -= 3;
                    break;
            }

            /**
             * * ************************************************************** */
            if ($container->verified_by_user_id) {

                $stamp_file = "stamp_" . $container->manager_user_id . "_" . $container->login_id . ".jpg";
                $image_file_path = FCPATH . "uploads/users/managers/stamps/$stamp_file";
                if (!is_file($image_file_path)) {
                    $image_file_path = FCPATH . "uploads/users/managers/stamps/no_stamp.jpg";
                }
                $gdImage = imagecreatefromjpeg($image_file_path);

                $objDrawing = new PHPExcel_Worksheet_MemoryDrawing();
                $objDrawing->setName($container->manager);
                $objDrawing->setDescription($container->manager);
                $objDrawing->setImageResource($gdImage);
                $objDrawing->setRenderingFunction(PHPExcel_Worksheet_MemoryDrawing::RENDERING_JPEG);
                $objDrawing->setMimeType(PHPExcel_Worksheet_MemoryDrawing::MIMETYPE_DEFAULT);
                $objDrawing->setHeight(90);
                $objDrawing->setWidth(90);
                $objDrawing->setOffsetX(84);
                $objDrawing->setOffsetY(5);
                $objDrawing->setCoordinates('B' . $stamp_cell_row);
                $objDrawing->setWorksheet($sheet2);

                /**
                 * * ************************************************************* */
            }

            $objPHPExcel->addSheet($sheet2);

            $sheet2->getPageSetup()->setFitToWidth(1);
            $sheet2->getPageSetup()->setFitToHeight(0);
            $sheet2->getPageSetup()->setHorizontalCentered(1);
        }
        $objPHPExcel->removeSheetByIndex(0);
//        if($this->user->is_super_admin()){
////            die;
//        }
//        display_admin_debug($objPHPExcel, "array");
        return $objPHPExcel;
    }

    public function generate_purchased_but_not_received($report_data) {
        $inputFile = FCPATH . 'downloads/yards/purchased_but_not_received_temp.xlsx';

        $reader = new PHPExcel_Reader_Excel2007();
        $objPHPExcel = $reader->load($inputFile);
        $objPHPExcel->setActiveSheetIndex(0);
        $sheet = $objPHPExcel->getActiveSheet();
        $rowCount = 2;
        unset($report_data[0]);
        foreach ($report_data as $row) {
            $sheet->fromArray($row, NULL, "A" . $rowCount);
            $rowCount++;
        }

        $sheet->getPageSetup()->setRowsToRepeatAtTop(array(1, 1));
        $sheet->getPageSetup()->setFitToWidth(1);
        $sheet->getPageSetup()->setFitToHeight(0);
        $sheet->getPageSetup()->setHorizontalCentered(1);
        return $objPHPExcel;
    }

    public function generate_removed_shuppin_scrap($report_data) {
        $inputFile = FCPATH . 'downloads/yards/removed_shuppin_scrap_temp.xlsx';

        $reader = new PHPExcel_Reader_Excel2007();
        $objPHPExcel = $reader->load($inputFile);
        $objPHPExcel->setActiveSheetIndex(0);
        $sheet = $objPHPExcel->getActiveSheet();
        $rowCount = 2;
        unset($report_data[0]);
        foreach ($report_data as $row) {
            $sheet->fromArray($row, NULL, "A" . $rowCount);
            $rowCount++;
        }
        $sheet->getPageSetup()->setRowsToRepeatAtTop(array(1, 1));
        $sheet->getPageSetup()->setFitToWidth(1);
        $sheet->getPageSetup()->setFitToHeight(0);
        $sheet->getPageSetup()->setHorizontalCentered(1);
        return $objPHPExcel;
    }

    public function generate_purchase_data_acution_company_wise($data) {

        $inputFile = FCPATH . 'downloads/purchases/purchase_data_acution_company_wise_temp.xlsx';
        ob_clean();
//        print_r($data); die;
        $reader = new PHPExcel_Reader_Excel2007();
        $objPHPExcel = $reader->load($inputFile);
        $objPHPExcel->setActiveSheetIndex(0);
        $sheet = $objPHPExcel->getActiveSheet();
        $rowCount = 2;
//        unset($data[0]);
        foreach ($data as $row) {
            $sheet->fromArray($row, NULL, "A" . $rowCount);
            $rowCount++;
        }
        $sheet->getPageSetup()->setRowsToRepeatAtTop(array(1, 1));
        $sheet->getPageSetup()->setFitToWidth(1);
        $sheet->getPageSetup()->setFitToHeight(0);
        $sheet->getPageSetup()->setHorizontalCentered(1);
        return $objPHPExcel;
    }

    public function generate_bl_shipped_list_file($bls_shipped, $generate_download = TRUE) {

        $now = date('Y_m_d_H_i_s');
        $weekly_display_name = $bls_shipped['weekly_display_name'];
        $to_country_name = $bls_shipped['to_country_name'];

        $inputFile = FCPATH . 'downloads/invoices/templates/inv_temp_bls_shipped_list.xlsx';

        $reader = new PHPExcel_Reader_Excel2007();
        $objPHPExcel = $reader->load($inputFile);

        $objPHPExcel->setActiveSheetIndex(0);
        $sheet_temp = $objPHPExcel->getActiveSheet();

        $invoice_row_nos = array();

        foreach ($bls_shipped['vessels'] as $vessel) {

            foreach ($vessel['cities'] as $city) {
//                var_dump(isset($city['from_city_name']));
//                kas_pr($city['from_city_name']);
                $row_no = 4;
                $total_bls = 0;
                $total_containers = 0;
                $sheet = $sheet_temp->copy();
                $title = substr($vessel['vessel_name'], 0, 10) . '_' . substr($city['from_city_name'], 0, 15);
                $sheet->setTitle($title);

                $sheet->setCellValue('B2', 'B/L NO.LIST SHIPPED ON ' . $weekly_display_name);
                $sheet->setCellValue('B3', $city['from_city_name']);
                $sheet->setCellValue('B195', $city['from_city_name'] . ' / ' . $to_country_name);

                $i = 0;
                $no_of_lines = array();
                $page_breaks = array();
                foreach ($city['invoices'] as $invoice) {
//                $invoice_row_nos[$i]['start'] = $row_no;
//                $start = $row_no;
//                $end = $row_no + count($invoice['containers']) + 1;

                    $total_bls++;

                    $sheet->setCellValue('B' . $row_no, $invoice['etd_date']);
                    $sheet->setCellValue('C' . $row_no, $invoice['eta_date']);
                    $sheet->setCellValue('D' . $row_no, $invoice['bl_no']);
                    $sheet->setCellValue('D' . ($row_no + 1), $invoice['invoice_no']);
                    $sheet->setCellValue('E' . $row_no, $invoice['vessel_name']);
                    $sheet->setCellValue('F' . $row_no, $invoice['voyage_no']);
                    foreach ($invoice['containers'] as $container) {
//                    $invoice_row_nos[$invoice['invoice_id']] ++;
                        $total_containers++;
                        $sheet->setCellValue('G' . $row_no, $container['container_no']);
                        $row_no++;
                    }
                    $no_of_lines[] = $row_no;
                    $row_no++;
//                $invoice_row_nos[$i]['end'] = $row_no;
//                $i++;
                }
                $sheet->setCellValue('D200', $total_bls);
                $sheet->setCellValue('G200', $total_containers . ' VANS');

                $pageBreaks = array();
                $page_lines = 50;
                foreach ($no_of_lines as $index => $node) {

                    if (isset($no_of_lines[$index + 1])) {

                        if ($node <= $page_lines && ($no_of_lines[$index + 1] > $page_lines)) {
//                        $pageBreaks[] = "A" . ($node - 1);
                            $pageBreaks[] = "A" . ($node);
//                        echo("TRUE = Other - A" . $node['value'] . "<br>");

                            $page_lines += 50;
                        }
//                echo("<hr>");
                    }
                }

                $sheet->getPageSetup()->setRowsToRepeatAtTop(array(2, 3));

//            kas_pr($pageBreaks);
//            die;

                $sheet->getPageSetup()->setFitToWidth(1);
                $sheet->getPageSetup()->setFitToHeight(0);
                $sheet->getPageSetup()->setHorizontalCentered(1);
                foreach ($pageBreaks as $pb) {
                    $sheet->setBreak($pb, PHPExcel_Worksheet::BREAK_ROW);
                }

                $blank_rows = 195 - $row_no;
//            echo($row_no . ' x ' . $blank_rows . '<br>');
                while ($blank_rows > 50) {
//                echo('Blank Rows ' . $blank_rows . '\n');
                    $sheet->removeRow($row_no, 50);
                    $blank_rows -= 50;
                }

//            $row_no;

                $objPHPExcel->addSheet($sheet);
            }
        }

//        die;
        $objPHPExcel->removeSheetByIndex(0);

        $file_name = 'BL_SHIPPED_' . $to_country_name . '_' . $weekly_display_name . '.xlsx';
        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
        if ($generate_download) {

            $target_dir = $_SERVER['DOCUMENT_ROOT'] . BASE_FOLDER . 'downloads/invoices/bls_shipped/';
            $year = date('Y');
            $month = date('m');

            $ftp_conn = $this->AppModel->get_ftp_conn();

            if (!is_writable($target_dir)) {
                ftp_chmod($ftp_conn, 0777, $target_dir);
            }

            if (!is_dir($target_dir . "$year/")) {
                if (!mkdir($target_dir . "$year/")) {
                    throw new Exception("*Contact Admin - Year Folder Error");
                }
            }
            $target_dir .= $year . '/';
            if (!is_dir($target_dir . $month)) {
                if (!mkdir($target_dir . $month)) {
                    throw new Exception("*Contact Admin - Month Folder Error");
                }
            }
            $target_dir .= $month . '/';

            $save_file = $target_dir . $file_name;
            $objWriter->save($save_file);
            $download_link = base_url() . 'downloads/invoices/bls_shipped/' . $year . '/' . $month . '/' . $file_name;
            ftp_close($ftp_conn);
            return $download_link;
        } else {
            header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
            header("Content-Disposition: attachment; filename=$file_name");
            header("Cache-Control: max-age=0");

            $objWriter->save("php://output");
            exit;
        }
    }

    /**
     *
     * Use this function to generate simple excel files
     */
    public function generate_excel_file_common($data, $target, $generate_download = FALSE) {

        $now = date("Y_m_d_H_i_s");
        $file_name = "";
        $target_dir = "";
        $download_folder = "";
        $row_count = 2;
        switch ($target) {


				
			case 'videos':

                $download_folder = 'downloads/videos/';
                $target_dir = FCPATH . $download_folder;

                $row_count = 2;
                $file_name = "videos_" . $now . ".xlsx";
                $inputFile = FCPATH . 'downloads/temp/videos.xlsx';
                $export_data = $data['rows'];
                $columns_sequence = array(1 => 'snippetTitle','description','tags','thumnail', 'channelTitle', 'categoryId', 'categoryTitle','videoId','status');
                break;

            case 'string':

                $download_folder = 'downloads/videos/';
                $target_dir = FCPATH . $download_folder;

                $row_count = 2;
                $file_name = "videos_" . $now . ".xlsx";
                $inputFile = FCPATH . 'downloads/temp/videos_title.xlsx';
                $export_data = $data['rows'];
                $columns_sequence = array(1 => 'video_title','filter','category','description','tags', 'thumb','channel','video_id','status','url','first_name','last_names', 'email','message','rating_point','rating_comment','closeing_date','revenue_share');
                break;
        }
        $reader = new PHPExcel_Reader_Excel2007();
        $objPHPExcel = $reader->load($inputFile);

        $objPHPExcel->setActiveSheetIndex(0);
        $sheet = $objPHPExcel->getActiveSheet();

        $start_column = 1;
        $columns = array(1 => "A", "B", "C", "D", "E", "F", "G", "H", "I", "J",
            "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V",
            "W", "X", "Y", "Z");

        foreach ($export_data as $row) {

            //$sheet->setCellValue('B2', $invoice['master']->invoice_date);
            $column_count = count($row);
//            kas_pr($row);
            foreach ($row as $key => $cell_value) {

                $col_no = array_search($key, $columns_sequence);
                if ($col_no > 0) {
                    $col_ref = $columns[$col_no];
                    $date_reg = "/date/i";
                    if (preg_match($date_reg, $key)) {
//                        if($key == "shuppin_date"){
//                            display_admin_debug($cell_value, "variable");
//                        }
                        if (!empty($cell_value)) {
//                            if ($key == "shuppin_date") {
//                                display_admin_debug($cell_value, "variable");
//                            }
                            $found = FALSE;
//                            if($cell_value == '26/08/2016'){
//                                $found = TRUE;
//                                display_admin_debug($cell_value, "variable", FALSE);
//                            }
//                            $cell_value = date("Y-m-d", strtotime($cell_value));
                            $cell_value = PHPExcel_Shared_Date::PHPToExcel($cell_value);
//
//                            if ($cell_value == "1970-01-01") {
//                                $cell_value = "";
//                            }
//                            if($found){
//                                display_admin_debug($cell_value, "variable");
//                            }
                        }
                    }

                    $sheet->setCellValue($col_ref . $row_count, $cell_value);

                }
            }
//            echo("<br>");
            $row_count++;
        }
//        die;

        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
//        die;
        if (!$generate_download) {
            header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
            header("Content-Disposition: attachment; filename=$file_name");
            header("Cache-Control: max-age=0");

            $objWriter->save("php://output");
            exit;
        } else {


            $year = date('Y');
            $month = date('m');

            //$ftp_conn = $this->AppModel->get_ftp_conn();

//            echo($_SERVER['DOCUMENT_ROOT']);
//            echo("SS - $target_dir");
//            if (!is_writable($target_dir)) {
//                ftp_chmod($ftp_conn, 0777, $target_dir);
//            }

            if (!is_dir($target_dir . "$year/")) {
                if (!mkdir($target_dir . "$year/")) {
                    throw new Exception("*Contact Admin - Year Folder Error");
                }
            }
            $target_dir .= $year . '/';

//            if (!is_writable($target_dir)) {
//                ftp_chmod($ftp_conn, 0777, $target_dir);
//            }

            if (!is_dir($target_dir . $month)) {
                if (!mkdir($target_dir . $month)) {
                    throw new Exception("*Contact Admin - Month Folder Error");
                }
            }
            $target_dir .= $month . '/';
            /*if (!is_writable($target_dir)) {
                ftp_chmod($ftp_conn, 0777, $target_dir);
            }*/

//            echo($target_dir);
//            $result = ftp_chmod($ftp_conn, 0777, $target_dir);
//            var_dump($result);
            $save_file = $target_dir . $file_name;
            $objWriter->save($save_file);
            $download_link = base_url() . $download_folder . $year . '/' . $month . '/' . $file_name;
            //ftp_close($ftp_conn);
            return $download_link;
        }
    }

    public function generate_shipping_instructions_mongolia_sheet($invoice_id, $invoice) {
        $result_data = array();
        $result_data['bl_no'] = $invoice['master']->bl_no;
        $result_data['booking_no'] = $invoice['master']->booking_no;
        $result_data['invoice_no'] = $invoice['master']->invoice_no;

        if ($invoice['master']->from_city_name == "KOBE YARD A") {
            $invoice['master']->from_city_name = "KOBE";
        }
        if (stristr($invoice['master']->to_port_name, "Durban")) {
            $invoice['master']->to_port_name = "DURBAN";
        }

        //I29
        //255 - FAKOUKA
        if ($invoice['master']->from_city_id == 255) {
            $port_of_loading = "HAKATA, JAPAN";
        }
        //308 - YOKOHAMA VIA TOMAKOMAI
        else if ($invoice['master']->from_city_id == 308) {
            $port_of_loading = "YOKOHAMA, JAPAN";
        } else {

            $port_of_loading = $invoice['master']->from_city_name . ", " . $invoice['master']->from_country_name;
        }

        $port_of_discharge = $invoice['master']->to_port_name;
        $place_of_delivery = $invoice['master']->place_of_delievery;


        $shipping_consignee_office_address = str_replace("\m", "\n", $invoice['consignees']['shipping_consignee']->office_address);
        $shipping_consignee_office_name = $invoice['consignees']['shipping_consignee']->office_name;

        $notify_party = "";

        $notify_party .= str_replace("\m", "\n", $invoice['master']->notify_party);

        $inputFile = FCPATH . 'downloads/invoices/templates/inv_temp_shipping_instructions_mongolia.xlsx';

        $reader = new PHPExcel_Reader_Excel2007();
        $objPHPExcel = $reader->load($inputFile);

        /**
         * ********************************************
         */
        $objPHPExcel->setActiveSheetIndex(0);

        $sheet1 = $objPHPExcel->getActiveSheet();

        $sheet1->setCellValue('B9', $invoice['master']->invoice_office_name_eng . "\nNO.9-49 ONOHAMA-CHO CHUO-KU KOBE JAPAN");

        $sheet1->setCellValue('B14', $shipping_consignee_office_name . "\n" . $shipping_consignee_office_address);
        $sheet1->setCellValue('B20', $notify_party);
        $sheet1->setCellValue('B25', $invoice['master']->pre_carriage);
        $sheet1->setCellValue('B29', $invoice['master']->vessel_name);

        $sheet1->setCellValue('B38', $invoice['master']->marks_and_numbers);

        $sheet1->setCellValue('B55', $invoice['master']->route_detail);
        $sheet1->setCellValue('B57', $invoice['master']->other_information);

        $sheet1->setCellValue('H29', $invoice['master']->voyage_no);

        $sheet1->setCellValue('I25', $invoice['master']->place_of_receipt);

        $sheet1->setCellValue('I29', $port_of_loading);
        $sheet1->setCellValue('B31', $port_of_discharge);
        $sheet1->setCellValue('I31', $place_of_delivery);

        $sheet1->setCellValue('K38', $invoice['master']->invoice_type_name);
        $sheet1->setCellValue('K56', $invoice['master']->invoice_no);

        $sheet1->setCellValue('M27', $invoice['master']->cut_date);
        $sheet1->setCellValue('M29', $invoice['master']->shipment_date);
        $sheet1->setCellValue('N3', $invoice['master']->invoice_date);
        $sheet1->setCellValue('N5', $invoice['master']->person_incharge);

        $sheet1->setCellValue('N8', $invoice['master']->booking_no);
        $sheet1->setCellValue('N10', $invoice['master']->shipping_company_name);
        $sheet1->setCellValue('N12', $invoice['master']->service);
        $sheet1->setCellValue('N14', $invoice['master']->bl_to_be_released_at);
        $sheet1->setCellValue('N16', $invoice['master']->ocean_freight_payable_at);
        $sheet1->setCellValue('N18', $invoice['master']->freight);
        $sheet1->setCellValue('N21', $invoice['master']->invoice_no);

        $all_containers_total_units_count = 0;
        $all_containers_total_pcs_count = 0;
        $all_containers_total_amount = 0;
        $all_containers_total_g_weight = 0;
        $all_containers_no_commercial_total_amount = 0;
        $all_containers_tera_weight = 0;
        $all_containers_m3 = 0;

        $total_containers = 0;
        $total_cars = 0;
        $invoice_total_amount = 0;
        $invoice_total_weight = 0;
        $invoice_total_units_count = 0;
        $invoice_total_pcs_count = 0;
        $invoice_no_commercial_total_amount = 0;
        $invoice_str_quantity = "";
        $str_invoice_no_commercial = "";

        $row = 42;
        foreach ($invoice['containers'] as $container) {
            $container_units_count = 0;
            $container_pcs_count = 0;
            $container_g_weight = 0;
            $str_quanity = "";

            $total_containers++;

            $container_m3_total = 0;
            foreach ($container['details']['cars'] as $car) {
                $total_cars++;
                $container_units_count++;
                $container_g_weight += $car->weight;
                $all_containers_total_amount += $car->amount;
//                $m3 = ($car->dimension_l * $car->dimension_w * $car->dimension_h) / 1000;
                $m3 = ($car->dimension_l * $car->dimension_w * $car->dimension_h) / 1000000;
                $m3 = round($m3, 3);
                $container_m3_total += $m3;
            }
            $all_containers_m3 += $container_m3_total;
            foreach ($container['details']['others'] as $other) {
                $other_amount = $other->unit_price * $other->quantity;
                if ($other->commercial) {
                    $all_containers_total_amount += $other_amount;
                } else {
                    $all_containers_no_commercial_total_amount += $other_amount;
                }
                if ($other->quantity_unit == "UNITS") {
                    $container_units_count += $other->quantity;
                } else if ($other->quantity_unit == "PCS") {
                    $container_pcs_count += $other->quantity;
                }
                $container_g_weight += $other->weight;
            }
            $all_containers_total_units_count += $container_units_count;
            $all_containers_total_pcs_count += $container_pcs_count;


            if ($container_units_count && $container_pcs_count) {
                $str_quanity = "$container_units_count UNITS & $container_pcs_count PCS";
            } else if ($container_units_count && !$container_pcs_count) {
                $str_quanity = "$container_units_count UNITS";
            } else if (!$container_units_count && $container_pcs_count) {
                $str_quanity = "$container_pcs_count PCS";
            }

            $all_containers_total_g_weight += $container_g_weight;
            $sheet1->setCellValue('C' . $row, $container['container_no'])
                    ->setCellValue('H' . $row, $container['seal_no'])
                    ->setCellValue('I' . $row, $container_m3_total)
                    ->setCellValue('J' . $row, $container_g_weight)
                    ->setCellValue('K' . $row, "KGS")
                    ->setCellValue('L' . $row, $container['total_weight'])
                    ->setCellValue('N' . $row, "KGS")
                    ->setCellValue('O' . $row, $str_quanity)
                    ->setCellValue('P' . $row, $container['leading_character']);
            $all_containers_tera_weight += $container['total_weight'];
            $row++;
        }
        $row += 2;

        $str_quanity_total = "";
        if ($all_containers_total_units_count && $all_containers_total_pcs_count) {
            $str_quanity_total = "$all_containers_total_units_count UNITS & $all_containers_total_pcs_count PCS";
        } else if ($all_containers_total_units_count && !$all_containers_total_pcs_count) {
            $str_quanity_total = "$all_containers_total_units_count UNITS";
        } else if (!$all_containers_total_units_count && $all_containers_total_pcs_count) {
            $str_quanity_total = "$all_containers_total_pcs_count PCS";
        }

        $sheet1->setCellValue("H38", "$total_containers CONTAINERS");
        $sheet1->setCellValue("H39", $str_quanity_total);

        $row++;
        $all_containers_tera_weight = number_format($all_containers_tera_weight);
        $all_containers_total_g_weight = number_format($all_containers_total_g_weight);
        $sheet1->setCellValue("J54", $all_containers_total_g_weight);
        $sheet1->setCellValue("I54", $all_containers_m3);
        $sheet1->setCellValue("L54", "$all_containers_tera_weight");

        $sheet1->setCellValue("O54", $str_quanity_total);

        /**
         * *********************************************************************
         */
        $sheet1->getPageSetup()->setFitToWidth(1);
        $sheet1->getPageSetup()->setFitToHeight(0);
        $sheet1->getPageSetup()->setHorizontalCentered(1);

        return $objPHPExcel;
    }


    public function generate_custom_container_sheet($cc_car) {
        $result_data = array();
        extract($cc_car);
        $now = date("Y_m_d_H_i_s");
        $download_folder = 'downloads/mukechi/car_cost/';
        $target_dir = $_SERVER['DOCUMENT_ROOT'] . BASE_FOLDER . $download_folder;
        $file_name = "car_cost_" . $now . ".xlsx";
        $inputFile = FCPATH . 'downloads/mukechi/car_cost_temp.xlsx';

        $reader = new PHPExcel_Reader_Excel2007();
        $objPHPExcel = $reader->load($inputFile);

        /**
         * ********************************************
         */
        $objPHPExcel->setActiveSheetIndex(0);

        $sheet1 = $objPHPExcel->getActiveSheet();

        $default_border = array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array('rgb'=>'FF000000')
        );
        $style_for_total = array(
            'borders' => array(
                'allborders' => $default_border
            ),
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb'=>'E1E0F7'),
            ),
            'font' => array(
                'bold' => true,
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
            )
        );
        $style_for_total_values = array(
            'borders' => array(
                'allborders' => $default_border
            ),
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb'=>'fccf3a'),
            ),
            'font' => array(
                'bold' => true,
            )
        );
        $style_header = array(
            'borders' => array(
                'allborders' => $default_border
            ),
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb'=>'E1E0F7'),
            ),
            'font' => array(
                'bold' => true,
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT
            )
        );


        $total['unit_price'] = 0;
        $total['transportation_cost'] = 0;
        $total['documentation_cost'] = 0;
        $total['vanning_cost'] = 0;
        $total['dhl_cost'] = 0;
        $total['inspection_cost'] = 0;
        $total['repair_cost'] = 0;
        $total['penalty_other_cost'] = 0;
        $total['vehicle_total_price'] = 0;
        $total['dealer_price'] = 0;
        $total['company_profit'] = 0;
        $total['dealer_profit'] = 0;
        $row = 2;
        $symbol = '¥';
        //$container_no = '';

        foreach ($car_with_container_no as  $container_no => $car_container) {
            foreach ($car_container as $car) {
                    
                
                //print_r($car); exit;
                $stock_no = $car->purchase_car_id;
                $maker_model = $car->maker_name.' / '.$car->model_name;
                $chassis_no = $car->chassis_no;
                $year = $car->registration_year;
                $invoice_no = $car->invoice_no;


                $unit_price = $car->contract_price + $car->successful_bid_charge;

                if($unit_price > 0){
                    $total['unit_price'] += $unit_price;
                    $unit_price = $symbol.number_format($unit_price );
                }else{
                    $unit_price = '';
                }

                $transportation_cost = $car->transportation_cost;
                if($transportation_cost > 0){
                    $total['transportation_cost'] += $transportation_cost;
                    $transportation_cost = $symbol.number_format($transportation_cost);
                }else{
                    $transportation_cost = '';
                }

                $documentation_cost = $car->documentation_cost;
                if($documentation_cost > 0){
                    $total['documentation_cost'] += $documentation_cost;
                    $documentation_cost = $symbol.number_format($documentation_cost);
                }else{
                    $documentation_cost = '';
                }

                $vanning_cost = $car->vanning_cost;
                if($vanning_cost > 0){
                    $total['vanning_cost'] += $vanning_cost;
                    $vanning_cost = $symbol.number_format($vanning_cost);
                }else{
                    $vanning_cost = '';
                }

                $dhl_cost = $car->dhl_cost;
                if($dhl_cost > 0){
                    $total['dhl_cost'] += $dhl_cost;
                    $dhl_cost = $symbol.number_format($dhl_cost);
                }else{
                    $dhl_cost = '';
                }

                $inspection_cost = $car->inspection_cost;
                if($inspection_cost > 0){
                    $total['inspection_cost'] += $inspection_cost;
                    $inspection_cost = $symbol.number_format($inspection_cost);
                }else{
                    $inspection_cost = '';
                }

                $repair_cost = $car->repair_cost;
                if($repair_cost > 0){
                    $total['repair_cost'] += $repair_cost;
                    $repair_cost = $symbol.number_format($repair_cost);
                }else{
                    $repair_cost = '';
                }

                $penalty_other_cost = $car->penalty + $car->other_cost;
                if($penalty_other_cost > 0){
                    $total['penalty_other_cost'] += $penalty_other_cost;
                    $penalty_other_cost = $symbol.number_format($penalty_other_cost);
                }else{
                    $penalty_other_cost = '';
                }

                $vehicle_total_price = $car->vehicle_total_price;
                if($vehicle_total_price > 0){
                    $total['vehicle_total_price'] += $vehicle_total_price;
                    $vehicle_total_price = $symbol.number_format($vehicle_total_price);
                }else{
                    $vehicle_total_price = '';
                }

                $dealer = '';
                $dealer_country = $this->location->get_country_name($car->dealer_country_id);
                if($dealer_country == 'n/a'){ $dealer_country=''; }
                $consigne = $this->location->get_consignee($car->dealer_consignee_id);
                $office = $consigne->office_name;
                $dealer_price = $car->dealer_price;
                if($dealer_price > 0){
                    $total['dealer_price'] += $dealer_price;
                    $dealer_price = $symbol.number_format($dealer_price);
                }else{
                    $dealer_price = '';
                }
                $dealer_remarks = $car->dealer_remarks;
                $company_profit = $car->company_profit_amount;
                if($company_profit > 0){
                    $total['company_profit'] += $company_profit;
                    $company_profit = $symbol.number_format($company_profit);
                }else{
                    $company_profit = '';
                }
                $dealer_profit = $car->dealer_profit_amount;
                if($dealer_profit > 0){
                    $total['dealer_profit'] += $dealer_profit;
                    $dealer_profit = $symbol.number_format($dealer_profit);
                }else{
                    $dealer_profit = '';
                }
                

                $sheet1->setCellValue('A' . $row, $stock_no)
                        ->setCellValue('B' . $row, $maker_model)
                        ->setCellValue('C' . $row, $chassis_no)
                        ->setCellValue('D' . $row, $year)
                        ->setCellValue('E' . $row, $invoice_no)
                        ->setCellValue('F' . $row,  $unit_price)
                        ->setCellValue('G' . $row, $transportation_cost)
                        ->setCellValue('H' . $row, $documentation_cost)
                        ->setCellValue('I' . $row, $vanning_cost)
                        ->setCellValue('J' . $row, $dhl_cost)
                        ->setCellValue('K' . $row, $inspection_cost)
                        ->setCellValue('L' . $row, $repair_cost)
                        ->setCellValue('M' . $row, $penalty_other_cost)
                        ->setCellValue('N' . $row, $vehicle_total_price)
                        ->setCellValue('O' . $row, $dealer)
                        ->setCellValue('P' . $row, $dealer_country)
                        ->setCellValue('Q' . $row, $office)
                        ->setCellValue('R' . $row, $dealer_price)
                        ->setCellValue('S' . $row, $dealer_remarks)
                        ->setCellValue('T' . $row, $company_profit)
                        ->setCellValue('U' . $row, $dealer_profit);
                $row++;
            }

                
                foreach ($total as $key => $value) {
                    if($value > 0){
                        $value = $symbol.(number_format($value));
                    }else{
                        $value = '';
                    }
                    $total[$key] = $value;
                }
                    
                //$row++;
                $sheet1->setCellValue('A' . $row,' TOTAL FOR CONTAINER NO: '.$container_no);
                $sheet1->mergeCells('A'.$row.':E'.$row);
                $sheet1->getStyle('A'.$row.':U'.$row)->applyFromArray( $style_for_total );
                $sheet1->getStyle('F'.$row.':U'.$row)->applyFromArray( $style_for_total_values );
                $sheet1->setCellValue('F' . $row, $total['unit_price'])
                ->setCellValue('G' . $row, $total['transportation_cost'])
                ->setCellValue('H' . $row, $total['documentation_cost'])
                ->setCellValue('I' . $row, $total['vanning_cost'])
                ->setCellValue('J' . $row, $total['dhl_cost'])
                ->setCellValue('K' . $row, $total['inspection_cost'])
                ->setCellValue('L' . $row, $total['repair_cost'])
                ->setCellValue('M' . $row, $total['penalty_other_cost'])
                ->setCellValue('N' . $row, $total['vehicle_total_price'])
                ->setCellValue('O' . $row, $total['dealer_price'])
                ->setCellValue('T' . $row, $total['company_profit'])
                ->setCellValue('U' . $row, $total['dealer_profit']);


                foreach ($total as $key => $value) {
                    $total[$key] = 0;
                }
                $row += 2;
                
        }
        $row += 5;
        $sheet1->setCellValue('A' . $row,' CAR WITHOUT CONTAINERS ');
        $sheet1->mergeCells('A'.$row.':U'.$row);
        $sheet1->getStyle('A'.$row.':U'.$row)->applyFromArray( $style_header );
        $row++;


        foreach ($car_without_container_no as $car) {
            $stock_no = $car->purchase_car_id;
            //echo $stock_no; exit;
            $maker_model = $car->maker_name.' / '.$car->model_name;
            $chassis_no = $car->chassis_no;
            $year = $car->registration_year;
            $invoice_no = $car->invoice_no;


            $unit_price = $car->contract_price + $car->successful_bid_charge;

            if($unit_price > 0){
                $total['unit_price'] += $unit_price;
                $unit_price = $symbol.number_format($unit_price);
            }else{
                $unit_price = '';
            }

            $transportation_cost = $car->transportation_cost;
            if($transportation_cost > 0){
                $total['transportation_cost'] += $transportation_cost;
                $transportation_cost = $symbol.number_format($transportation_cost);
            }else{
                $transportation_cost = '';
            }

            $documentation_cost = $car->documentation_cost;
            if($documentation_cost > 0){
                $total['documentation_cost'] += $documentation_cost;
                $documentation_cost = $symbol.number_format($documentation_cost);
            }else{
                $documentation_cost = '';
            }

            $vanning_cost = $car->vanning_cost;
            if($vanning_cost > 0){
                $total['vanning_cost'] += $vanning_cost;
                $vanning_cost = $symbol.number_format($vanning_cost);
            }else{
                $vanning_cost = '';
            }

            $dhl_cost = $car->dhl_cost;
            if($dhl_cost > 0){
                $total['dhl_cost'] += $dhl_cost;
                $dhl_cost = $symbol.number_format($dhl_cost);
            }else{
                $dhl_cost = '';
            }

            $inspection_cost = $car->inspection_cost;
            if($inspection_cost > 0){
                $total['inspection_cost'] += $inspection_cost;
                $inspection_cost = $symbol.number_format($inspection_cost);
            }else{
                $inspection_cost = '';
            }

            $repair_cost = $car->repair_cost;
            if($repair_cost > 0){
                $total['repair_cost'] += $repair_cost;
                $repair_cost = $symbol.number_format($repair_cost);
            }else{
                $repair_cost = '';
            }

            $penalty_other_cost = $car->penalty + $car->other_cost;
            if($penalty_other_cost > 0){
                $total['penalty_other_cost'] += $penalty_other_cost;
                $penalty_other_cost = $symbol.number_format($penalty_other_cost);
            }else{
                $penalty_other_cost = '';
            }

            $vehicle_total_price = $car->vehicle_total_price;
            if($vehicle_total_price > 0){
                $total['vehicle_total_price'] += $vehicle_total_price;
                $vehicle_total_price = $symbol.number_format($vehicle_total_price);
            }else{
                $vehicle_total_price = '';
            }

            $dealer = '';
            $dealer_country = $this->location->get_country_name($car->dealer_country_id);
            if($dealer_country == 'n/a'){ $dealer_country=''; }
            $consigne = $this->location->get_consignee($car->dealer_consignee_id);
            $office = $consigne->office_name;
            $dealer_price = $car->dealer_price;
            if($dealer_price > 0){
                $total['dealer_price'] += $dealer_price;
                $dealer_price = $symbol.number_format($dealer_price);
            }else{
                $dealer_price = '';
            }
            $dealer_remarks = $car->dealer_remarks;
            $company_profit = $car->company_profit_amount;
            if($company_profit > 0){
                $total['company_profit'] += $company_profit;
                $company_profit = $symbol.number_format($company_profit);
            }else{
                $company_profit = '';
            }
            $dealer_profit = $car->dealer_profit_amount;
            if($dealer_profit > 0){
                $total['dealer_profit'] += $dealer_profit;
                $dealer_profit = $symbol.number_format($dealer_profit);
            }else{
                $dealer_profit = '';
            }
            

            $sheet1->setCellValue('A' . $row, $stock_no)
                    ->setCellValue('B' . $row, $maker_model)
                    ->setCellValue('C' . $row, $chassis_no)
                    ->setCellValue('D' . $row, $year)
                    ->setCellValue('E' . $row, $invoice_no)
                    ->setCellValue('F' . $row,  $unit_price)
                    ->setCellValue('G' . $row, $transportation_cost)
                    ->setCellValue('H' . $row, $documentation_cost)
                    ->setCellValue('I' . $row, $vanning_cost)
                    ->setCellValue('J' . $row, $dhl_cost)
                    ->setCellValue('K' . $row, $inspection_cost)
                    ->setCellValue('L' . $row, $repair_cost)
                    ->setCellValue('M' . $row, $penalty_other_cost)
                    ->setCellValue('N' . $row, $vehicle_total_price)
                    ->setCellValue('O' . $row, $dealer)
                    ->setCellValue('P' . $row, $dealer_country)
                    ->setCellValue('Q' . $row, $office)
                    ->setCellValue('R' . $row, $dealer_price)
                    ->setCellValue('S' . $row, $dealer_remarks)
                    ->setCellValue('T' . $row, $company_profit)
                    ->setCellValue('U' . $row, $dealer_profit);
            $row++;
        }

        if(count($car_without_container_no) > 0){
                
            foreach ($total as $key => $value) {
                if($value > 0){
                    $value = $symbol.number_format($value);
                }else{
                    $value = '';
                }
                $total[$key] = $value;
            }
            
            $row++;
            $sheet1->setCellValue('A' . $row,' Total ');
            $sheet1->mergeCells('A'.$row.':E'.$row);
            $sheet1->getStyle('A'.$row.':E'.$row)->applyFromArray( $style_for_total );
            $sheet1->getStyle('F'.$row.':U'.$row)->applyFromArray( $style_for_total_values );
            
            $sheet1->setCellValue('F' . $row, $total['unit_price'])
            ->setCellValue('G' . $row, $total['transportation_cost'])
            ->setCellValue('H' . $row, $total['documentation_cost'])
            ->setCellValue('I' . $row, $total['vanning_cost'])
            ->setCellValue('J' . $row, $total['dhl_cost'])
            ->setCellValue('K' . $row, $total['inspection_cost'])
            ->setCellValue('L' . $row, $total['repair_cost'])
            ->setCellValue('M' . $row, $total['penalty_other_cost'])
            ->setCellValue('N' . $row, $total['vehicle_total_price'])
            ->setCellValue('O' . $row, $total['dealer_price'])
            ->setCellValue('T' . $row, $total['company_profit'])
            ->setCellValue('U' . $row, $total['dealer_profit']);


            foreach ($total as $key => $value) {
                $total[$key] = 0;
            }
        }

        $sheet1->getPageSetup()->setFitToWidth(1);
        $sheet1->getPageSetup()->setFitToHeight(0);
        $sheet1->getPageSetup()->setHorizontalCentered(1);

        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
        if (isset($generate_download) && !$generate_download) {
            header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
            header("Content-Disposition: attachment; filename=$file_name");
            header("Cache-Control: max-age=0");

            $objWriter->save("php://output");
            exit;
        } else {


            $year = date('Y');
            $month = date('m');

            $ftp_conn = $this->AppModel->get_ftp_conn();
            if (!is_writable($target_dir)) {
                ftp_chmod($ftp_conn, 0777, $target_dir);
            }

            if (!is_dir($target_dir . "$year/")) {
                if (!mkdir($target_dir . "$year/")) {
                    throw new Exception("*Contact Admin - Year Folder Error");
                }
            }
            $target_dir .= $year . '/';

            if (!is_writable($target_dir)) {
                ftp_chmod($ftp_conn, 0777, $target_dir);
            }

            if (!is_dir($target_dir . $month)) {
                if (!mkdir($target_dir . $month)) {
                    throw new Exception("*Contact Admin - Month Folder Error");
                }
            }
            $target_dir .= $month . '/';
            if (!is_writable($target_dir)) {
                ftp_chmod($ftp_conn, 0777, $target_dir);
            }
            $save_file = $target_dir . $file_name;
            $objWriter->save($save_file);
            $download_link = base_url() . $download_folder . $year . '/' . $month . '/' . $file_name;
            ftp_close($ftp_conn);
            return $download_link;
        }
        return $objPHPExcel;
    }

}
