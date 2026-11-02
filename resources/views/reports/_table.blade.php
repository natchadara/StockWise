@if ($items->isEmpty())
    <p class="empty-state">There are no low-stock items right now.</p>
@else
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>SKU</th>
                    <th>Item</th>
                    <th>Category</th>
                    <th>Supplier</th>
                    <th>Quantity</th>
                    <th>Reorder level</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $item)
                    <tr>
                        <td>{{ $item->sku }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->category->name }}</td>
                        <td>{{ $item->supplier->name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ $item->reorder_level }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
