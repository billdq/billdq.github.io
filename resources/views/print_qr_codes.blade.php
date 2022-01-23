<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Workflow</title>

    <link rel="stylesheet" href="/css/interface.css">
    <script src="/js/jquery-3.5.1.js"></script>
    <script src="/js/paged.polyfill.js"></script>
    <style>
    @page {
        size: 62mm 85mm;
        margin: 4mm 4mm;
    }

    .page-break {
        break-after: page;
        font-size: vmin;
    }

    img {
        max-width: 100%;
        max-height: 100%;
        display: block;
    }

    span {
        display: block;
    }
    </style>
</head>

<body>
    @foreach($qrCodes as $qr)
    <div class="page-break">
        <img src="/qr/{{ $qr->value.'.png ' }}" />
        <span>Category: {{ $catLabel }}</span>
        <span>Order No.: {{ $orderNo }}</span>
        <span>QR Code: {{ $qr->value }}</span>
    </div>
    @endforeach
    <script>
    var count = 0;
    var total = {{ sizeof($qrCodes) }};
    class customHandler extends Paged.Handler {
        constructor(chunker, polisher, caller) {
            super(chunker, polisher, caller);
        }

        beforeParsed(content) {
            console.log("beforeParsed");
        }

        afterParsed(parsed) {
            console.log("afterParsed");
        }


        afterPageLayout(pageFragment, page) {
            count++;
            if (count == total) {
                console.log("afterPageLayout");
                window.print();
                window.close();
            }
        }
    }

    Paged.registerHandlers(customHandler);
    </script>
</body>

</html>