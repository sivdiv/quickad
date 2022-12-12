<?php

if(!isset($_GET['page']))
    $_GET['page'] = 1;

$limit = 4;

if(checkloggedin()) {
    $ses_userdata = get_user_data($_SESSION['user']['username']);

    $author_image = $ses_userdata['image'];
    $transactions = array();
    $count = 0;

    $rows = ORM::for_table($config['db']['pre'].'transaction')
        ->where('seller_id',$_SESSION['user']['id'])
        ->order_by_desc('id')
        ->find_many();
    $total_item = count($rows);
    foreach ($rows as $row)
    {
        $currency_code = ($row['currency_code'] != null)? $row['currency_code']: $config['currency_code'];
        $transactions[$count]['id'] = $row['id'];
        $transactions[$count]['product_id'] = $row['product_id'];
        $transactions[$count]['product_name'] = $row['product_name'];
        $transactions[$count]['amount'] = price_format($row['amount'],$currency_code);
        $transactions[$count]['payment_by'] = $row['transaction_gatway'];
        $transactions[$count]['time'] = date('d M Y h:i A', $row['transaction_time']);

        $pro_url = create_slug($row['product_name']);


        $premium = '';
        if($row['transaction_method'] == 'Subscription'){
            $premium = '<span class="label label-default">'.$lang['MEMBERSHIP'].'</span>';
            $product_link = '#';
            $transactions[$count]['product_link'] = $product_link;
        }
        elseif($row['transaction_method'] == 'banner-advertise'){
            $premium = '<span class="label label-default">'.$lang['BANNER_ADVERTISE'].'</span>';
            $product_link = '#';
            $transactions[$count]['product_link'] = $product_link;
        }
        else{
            $featured = $row['featured'];
            $urgent = $row['urgent'];
            $highlight = $row['highlight'];


            if ($featured == "1") {
                $premium = $premium . '<span class="label label-warning">'.$lang['FEATURED'].'</span>';
            }

            if ($urgent == "1") {
                $premium = $premium . '<span class="label label-success">'.$lang['URGENT'].'</span>';
            }

            if ($highlight == "1") {
                $premium = $premium . '<span class="label label-info">'.$lang['HIGHLIGHT'].'</span>';
            }

            $product_link = $link['POST-DETAIL'].'/' . $row['product_id'] . '/'.$pro_url;
            $transactions[$count]['product_link'] = $product_link;
        }

        $t_status = $row['status'];
        $status = '';
        if ($t_status == "success") {
            $status = '<span class="label label-success">'.$lang['SUCCESS'].'</span>';
        } elseif ($t_status == "pending") {
            $status = '<span class="label label-warning">'.$lang['PENDING'].'</span>';
        } elseif ($t_status == "failed") {
            $status = '<span class="label label-danger">'.$lang['FAILED'].'</span>';
        }else{
            $status = '<span class="label label-danger">'.$lang['CANCEL'].'</span>';
        }

        $transactions[$count]['premium'] = $premium;
        $transactions[$count]['status'] = $status;
        $transactions[$count]['invoice'] = $t_status == "success" ? $link['INVOICE'].'/'.$row['id']:'';

        $count++;
    }
    // Output to template
    $page = new HtmlTemplate ('templates/' . $config['tpl_name'] . '/transaction.tpl');
    $page->SetParameter ('OVERALL_HEADER', create_header($lang['TRANSACTION']));
    $page->SetLoop ('TRANSACTIONS', $transactions);
    $page->SetLoop ('PAGES', pagenav($total_item,$_GET['page'],20,$link['TRANSACTION'] ,0));
    $page->SetParameter ('TOTALITEM', $total_item);
    $page->SetLoop ('HTMLPAGE', get_html_pages());
    $page->SetParameter('COPYRIGHT_TEXT', get_option("copyright_text"));
    $page->SetParameter ('AUTHORUNAME', ucfirst($ses_userdata['username']));
    $page->SetParameter ('AUTHORNAME', ucfirst($ses_userdata['name']));
    $page->SetParameter ('AUTHORIMG', $author_image);
    $page->SetParameter ('OVERALL_FOOTER', create_footer());

    $page->CreatePageEcho();
}
else{
    error($lang['PAGE_NOT_FOUND'], __LINE__, __FILE__, 1);
    exit();
}
?>