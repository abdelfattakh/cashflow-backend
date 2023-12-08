<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        table {
            border: 1px solid #ccc;
            border-collapse: collapse;
            margin: 0;
            padding: 0;
            width: 100%;
            table-layout: fixed;
        }

        table caption {
            font-size: 1.5em;
            margin: .5em 0 .75em;
        }

        table tr {
            background-color: #f8f8f8;
            border: 1px solid #ddd;
            padding: .35em;
        }

        table th,
        table td {
            padding: .625em;
            text-align: center;
        }

        table th {
            font-size: .85em;
            letter-spacing: .1em;
            text-transform: uppercase;
        }

        @media screen and (max-width: 600px) {
            table {
                border: 0;
            }

            table caption {
                font-size: 1.3em;
            }

            table thead {
                border: none;
                clip: rect(0 0 0 0);
                height: 1px;
                margin: -1px;
                overflow: hidden;
                padding: 0;
                position: absolute;
                width: 1px;
            }

            table tr {
                border-bottom: 3px solid #ddd;
                display: block;
                margin-bottom: .625em;
            }

            table td {
                border-bottom: 1px solid #ddd;
                display: block;
                font-size: .8em;
                text-align: right;
            }

            table td::before {
                /*
                * aria-label has no advantage, it won't be read inside a table
                content: attr(aria-label);
                */
                content: attr(data-label);
                float: left;
                font-weight: bold;
                text-transform: uppercase;
            }

            table td:last-child {
                border-bottom: 0;
            }
        }

        /* general styling */
        body {
            font-family: "Open Sans", sans-serif;
            line-height: 1.25;
        }

    </style>
</head>
<body>
<p> Task Today</p>
<
<table>
    <caption>Today Task</caption>
    <thead>
    <tr>



        <th scope="col">Project value</th>
        <th scope="col">Company name</th>
        <th scope="col">Bank name</th>
        <th scope="col">Item value</th>
        <th scope="col">Item name</th>


    </tr>
    </thead>
    <tbody>
    @foreach ($maildata as $items)
        <tr>
            <td data-label="Account">{{$items['project_name']}}</td>
            <td data-label="Account">{{$items['company_name']}}</td>
            <td data-label="Account">{{$items['bank_name']}}</td>
            <td data-label="Account">{{$items['value']}}</td>
            <td data-label="Account">{{$items['name']}}</td>

        </tr>
    @endforeach


    </tbody>
</table>
</body>

</html>
