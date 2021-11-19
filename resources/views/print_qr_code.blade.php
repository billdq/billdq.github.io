<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Workflow</title>

    <script src="/js/jquery-3.5.1.js"></script>
    <script src="/js/qrcode.js"></script>
    <script>
        $(document).ready(function() {
            new QRCode(document.getElementById("qr_code"), "{{ $order->qr_code }}");
        });
    </script>
</head>

<body>
    <table style="border: 1px solid black; border-collapse: collapse;">
    <tr><td>
        <div id="qr_code"></div>
        Recycle Order Title: {{ $order->title }}<br>
        Weight: {{ $order->weight }}<br>
        QR Code: {{ $order->qr_code }}<br>
        <br>
    </td></tr>
    </table>
</body>

</html>