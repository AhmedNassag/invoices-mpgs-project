import {usePage} from "@inertiajs/react";

export default function InvoiceHeader() {
    const {can} = usePage().props;

    return <tr>
        <th>#</th>
        <th>Name</th>
        <th>Date</th>
        <th>Payment Status</th>
        <th>Paid Amount</th>
        <th>Due Amount</th>
        <th>Total Amount</th>
        {can?.has_action_permission && <th>Action</th>}
    </tr>
}