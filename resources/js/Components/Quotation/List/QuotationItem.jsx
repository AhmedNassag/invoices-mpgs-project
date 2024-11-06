import {router, usePage} from "@inertiajs/react";
import {toast} from "react-toastify";

export default function QuotationItem({quotation}) {
    const {can} = usePage().props;

    const deleteItem = () => {
        router.delete(route('admin.quotation.destroy', quotation.id), {
            onBefore: () => confirm('Are you sure you want to delete this quotation?'),
            onFinish:() => {
                toast.success("Quotation deleted.");
            }
        });
    }

    return <tr>
        <td>{quotation.auto_id}</td>
        <td>{quotation.user.name}</td>
        <td>{quotation.date}</td>
        <td>{quotation.payment_status_name}</td>
        <td>{quotation.payment_amount}</td>
        <td>{quotation.due_amount}</td>
        <td>{quotation.total_amount}</td>
        <td>
            {can.quotation_show && <a href={route('admin.quotation.show', quotation.id)} className="btn btn-success btn-sm mr-2"
               data-toggle="tooltip" data-placement="top" title="View">
                <i className="far fa-check-square"></i>
            </a>}

            {can.quotation_show && <a href={route('admin.quotation.edit', quotation.id)} className="btn btn-primary btn-sm mr-2"
               data-toggle="tooltip" data-placement="top" title="Edit">
                <i className="fas fa-edit"></i>
            </a>}

            {can.quotation_show && <button className="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Delete" onClick={deleteItem}>
                <i className="fas fa-trash"></i>
            </button>}
        </td>
    </tr>
}