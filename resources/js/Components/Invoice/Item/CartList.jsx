import InvoiceCartHeader from "./CartHeader.jsx";
import InvoiceCartItem from "./CartItem.jsx";
import {useInvoice} from "../../../Contexts/InvoiceContext.jsx";

export default function CartList() {
    const {items, addInvoiceItem, invoiceQuantity, invoiceSubtotal} = useInvoice();

    return <div className="col-sm-12">
        <table className="table table-sm table-bordered" width="100%" cellSpacing="0">
            <thead>
                <InvoiceCartHeader></InvoiceCartHeader>
            </thead>
            <tbody className="small-select2">
                {items.length > 0 && items.map((item, index) => {
                    return <InvoiceCartItem key={index} index={index} item={item}></InvoiceCartItem>
                })}
            </tbody>
            <tfoot>
                <tr className="d-flex">
                    <th className="col-5"></th>
                    <th className="col-2">{invoiceQuantity}</th>
                    <th className="col-2"></th>
                    <th className="col-2">{invoiceSubtotal.toFixed(2)}</th>
                    <th className="col-1"></th>
                </tr>
            </tfoot>
        </table>

        <button className="btn btn-sm btn-primary btn-block" onClick={addInvoiceItem}>
            <i className="fas fa-shopping-basket"></i> Add an Item
        </button>
        <hr/>
    </div>
}