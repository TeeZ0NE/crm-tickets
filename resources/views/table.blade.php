<?php
$data = [];

$data1 = json_decode(file_get_contents('https://secom.com.ua/tickets/uahosting_company.json'), true);
$data2 = json_decode(file_get_contents('https://secom.com.ua/tickets/adminvps.json'), true);
$data3 = json_decode(file_get_contents('https://secom.com.ua/tickets/skt.json'), true);
$data4 = json_decode(file_get_contents('https://secom.com.ua/tickets/hostiman.json'), true);
$data5 = json_decode(file_get_contents('https://secom.com.ua/tickets/secom.json'), true);
$data6 = json_decode(file_get_contents('https://secom.com.ua/tickets/coopertino.json'), true);

if ($data1 != null) {
    $data += $data1;
}
if ($data2 != null) {
    $data += $data2;
}
if ($data3 != null) {
    $data += $data3;
}
if ($data4 != null) {
    $data += $data4;
}
if ($data5 != null) {
    $data += $data5;
}
if ($data6 != null) {
    $data += $data6;
}

?>
        <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <link rel="stylesheet" href="https://bowercdn.net/c/jquery.tablesorter-2.1.4/css/blue/style.css">
    <script src="https://code.jquery.com/jquery-3.2.1.js"
            integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE=" crossorigin="anonymous"></script>
    <script src="https://bowercdn.net/c/jquery.tablesorter-2.1.4/js/jquery.tablesorter.js"></script>
    <script>
        $(function () {
            $("#myTable").tablesorter({sortList: [[4, 1]]});
            setInterval(function () {
                location.reload();
            }, 60000);
        });
    </script>
    <style>
        .coopertino td {
            background-color: #CECEF6 !important;
        }

        .ua-hosting td {
            background-color: #F6E3CE !important;
        }

        .AdminVPS td {
            background-color: #F6CECE !important;
        }

        .SKT td {
            background-color: #CEECF5 !important;
        }

        .HostiMan td {
            background-color: #d5fafa !important;
        }
    </style>
</head>
<body>
<table id="myTable" class="table table-striped table-hover tablesorter">
    <thead class="thead-inverse">
    <tr>
        <th>Department</th>
        <th>#</th>
        <th>Subject</th>
        <th>Replier</th>
        <th>Datelast</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($data as $key => $item): ?>
    <?php if(count($item) <= 1) continue ?>

    <tr class="<?= $item['department'] ?>">
        <td><?= $item['department'] ?></td>
        <td><?= $key ?></td>
        <td>
            <a target="_blank" href="<?php if ($item['department'] == 'coopertino') {
                echo "https://coopertino.ru:1500/billmgr?func=desktop&startpage=tickets&startform=tickets.edit&elid={$key}";
            } else if ($item['department'] == 'ua-hosting') {
                echo "https://billing.ua-hosting.company/admin/supporttickets.php?action=view&id={$key}";
            } else if ($item['department'] == 'SeCom') {
                echo "https://secom.com.ua/billing/admin/supporttickets.php?action=view&id={$key}";
            } else if ($item['department'] == 'SKT') {
                echo "https://skt.ru/manager/billmgr?func=desktop&startpage=tickets&startform=tickets.edit&elid={$key}";
            } else if ($item['department'] == 'HostiMan') {
                echo "https://cp.hostiman.ru/admin/supporttickets.php?action=view&id={$key}";
            } else {
                echo "https://my.adminvps.ru/admi/supporttickets.php?action=view&id={$key}";
            } ?>">
<!--                --><?php //if(gettype($item['subject']) == 'array') { echo ''; } else { echo $item['subject']; } ?>
                <?= $item['subject']; ?>
            </a>
        </td>
        <td><?= $item['replier'] ?></td>
        <td><?= $item['datelast'] ?></td>
    </tr>
    <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>


