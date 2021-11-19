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
            @foreach ($qrCodes as $qr)
            new QRCode(document.getElementById("{{ $qr->value }}"), "{{ $qr->value }}");
            @endforeach
        });
    </script>
</head>

<body>
    <table style="border: 1px solid black; border-collapse: collapse;">
    @foreach ($qrCodes as $qr)
    <tr><td>
        <div id="{{ $qr->value }}"></div>
        Category: {{ $catLabel }}<br>
        Order No.: {{ $orderNo }}<br>
        QR Code: {{ $qr->value }}<br>
        <br>
    </td></tr>
    @endforeach
    </table>
</body>

</html>