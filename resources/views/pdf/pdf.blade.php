<!DOCTYPE html>
<html lang="en">
<head>
    <title>Invoice</title>
    <style>
        h4 {
            margin: 0;
        }
        .w-full {
            width: 100%;
        }
        .w-half {
            width: 50%;
        }
        .margin-top {
            margin-top: 1.25rem;
        }
        .footer {
            font-size: 0.875rem;
            padding: 1rem;
            background-color: rgb(241, 245, 249);
        }
        table {
            width: 100%;
            border-spacing: 0;
        }
        table.products {
            font-size: 0.875rem;
        }
        table.products th,
        table.products td {
            padding: 0.5rem;
            text-align: left;
        }
        table.products th:first-child,
        table.products td:first-child {
            padding-left: 1rem;
        }
        table.products th:last-child,
        table.products td:last-child {
            padding-right: 1rem;
            text-align: right;
        }
        table.products tr:nth-child(even) {
            background-color: rgb(241, 245, 249);
        }
        table.products th {
            color: #1c1414;
        }
        .total {
            text-align: right;
            margin-top: 1rem;
            font-size: 0.875rem;
        }
    </style>
</head>
<body>
    <table class="w-full">
        <tr>
            <td>
                {{-- <img src="{{$base64}}" style="max-width: 200px; height: auto;"> --}}
                <img src="{{ $nerdflow }}" alt="Example Image" style="max-width: 150px; height: auto;">
    
            </td>
            <td class="w-half">
                <h2>Invoice ID: {{$randomNumber}}</h2>
            </td>
        </tr>
    </table>
 
    <div class="margin-top">
        <table class="w-full">
            <tr>
                <td class="w-half">
                    <div><h4>To:</h4></div>
                    <div>Mr. Hadi Butt</div>
                    
                </td>
                <td class="w-half">
                    <div><h4>From:</h4></div>
                    <div> {{auth()->user()->name}}</div>
                    <div>MCS</div>
                </td>
            </tr>
        </table>
    </div>
 
    <div class="margin-top">
        <table class="products">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Date</th>
                    <th>Quantity</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $key => $item)
                    <tr>
                        <td>{{$key+1}}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->detail }}</td>
                        <td>{{ $item->price }}</td>
                        <td>{{\Carbon\Carbon::parse($item->date)->format('d/m/Y') }}</td>
                        <td>{{ $item->quantity }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div class="total">
        Total Bill:
        <br>
        ${{$totalprice}}
    </div>
 
    <div class="footer margin-top">
        <div>Thank you</div>
        <div>&copy; NERDFLOW</div>
        {{-- <img src="{{ $nerdflow }}" alt="Example Image" style="max-width: 100px; height: auto;"> --}}
    </div>
</body>
</html>
