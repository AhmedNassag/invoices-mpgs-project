export default function CartHeader() {
    return (
        <tr className="d-flex">
            <th className="col-5">Product</th>
            <th className="col-2">Qty</th>
            <th className="col-2">Unit Price</th>
            <th className="col-2">Subtotal</th>
            <th className="col-1">Action</th>
        </tr>
    )
}