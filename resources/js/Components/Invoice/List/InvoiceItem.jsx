import {router, usePage} from "@inertiajs/react";
import {toast} from "react-toastify";

export default function InvoiceItem({invoice}) {
    const {can} = usePage().props;

    const deleteItem = () => {
        router.delete(route('admin.invoice.destroy', invoice.id), {
            onBefore: () => confirm('Are you sure you want to delete this invoice?'),
            onFinish:() => {
                toast.success("Invoice deleted.");
            }
        });
    }

    return <tr>
        <td>{invoice.auto_id}</td>
        <td>{invoice.user.name}</td>
        <td>{invoice.date}</td>
        <td>{invoice.payment_status_name}</td>
        <td>{invoice.payment_amount}</td>
        <td>{invoice.due_amount}</td>
        <td>{invoice.total_amount}</td>
        <td>
            {can.invoice_show && <a href={route('admin.invoice.show', invoice.id)} className="btn btn-success btn-sm mr-2"
               data-toggle="tooltip" data-placement="top" title="View">
                <i className="far fa-check-square"></i>
            </a>}

            {can.invoice_edit && invoice.payment_status === 5 && <a href={route('admin.invoice.edit', invoice.id)} className="btn btn-primary btn-sm mr-2"
               data-toggle="tooltip" data-placement="top" title="Edit">
                <i className="fas fa-edit"></i>
            </a>}


            {can.invoice_destroy && invoice.payment_status === 5 && <button className="btn btn-danger btn-sm mr-2" data-toggle="tooltip" data-placement="top"
                    title="Delete" onClick={deleteItem}>
                <i className="fas fa-trash"></i>
            </button>}


            {can.invoice_show && invoice.payment_status !== 15 && <a  href={route('admin.invoice.payment', [invoice.id, 5])} className="btn btn-warning btn-sm mr-2"
                data-toggle="tooltip" data-placement="top" title="Payment">
                <i className="far fa-credit-card"></i>
            </a>}

            {can.invoice_show && invoice.payment_status !== 5 && <a href={route('admin.invoice.payments', invoice.id)} className="btn btn-success btn-sm"
               data-toggle="tooltip" data-placement="top" title="Payment List">
                <i className="fas fa-list"></i>
            </a>}
        </td>
    </tr>
}