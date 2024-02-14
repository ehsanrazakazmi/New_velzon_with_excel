<table>
    <thead>
    <tr>
        <th>Name</th>
        <th>Detail</th>
        <th>Price</th>
        <th>Date</th>
        <th>Quantity</th>
    </tr>
    </thead>
    <tbody>
    @foreach($invoices as $invoice)
        <tr>
            <td>{{ $invoice->name }}</td>
            <td>{{ $invoice->detail }}</td>
            <td>{{ $invoice->price }}</td>
            <td>{{ $invoice->date }}</td>
            <td>{{ $invoice->quantity }}</td>
        </tr>
    @endforeach
    </tbody>
</table>