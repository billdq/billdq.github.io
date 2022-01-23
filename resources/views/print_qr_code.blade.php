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
    @for ($i = 0; $i < $count; $i++)
    <div class="page-break">
        <img src="/qr/{{ $order->qr_code.'.png ' }}" />
        <span>Title: {{ $order->title }}</span>
        <span>Weight: {{ $order->weight }}</span>
        <span>QR Code: {{ $order->qr_code }}</span>
    </div>
    @endfor
    <script>
    var count = 0;
    var total = {{ $count }};
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