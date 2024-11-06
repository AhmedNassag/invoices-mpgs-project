import QuotationCartHeader from "./CartHeader.jsx";
import QuotationCartItem from "./CartItem.jsx";
import {useQuotation} from "../../../Contexts/QuotationContext.jsx";

export default function CartList() {
    const {items, addQuotationItem, quotationQuantity, quotationSubtotal} = useQuotation();

    return <div className="col-sm-12">
        <table className="table table-sm table-bordered" width="100%" cellSpacing="0">
            <thead>
                <QuotationCartHeader></QuotationCartHeader>
            </thead>
            <tbody className="small-select2">
                {items.length > 0 && items.map((item, index) => {
                    return <QuotationCartItem key={index} index={index} item={item}></QuotationCartItem>
                })}
            </tbody>
            <tfoot>
                <tr className="d-flex">
                    <th className="col-5"></th>
                    <th className="col-2">{quotationQuantity}</th>
                    <th className="col-2"></th>
                    <th className="col-2">{quotationSubtotal.toFixed(2)}</th>
                    <th className="col-1"></th>
                </tr>
            </tfoot>
        </table>

        <button className="btn btn-sm btn-primary btn-block" onClick={addQuotationItem}>
            <i className="fas fa-shopping-basket"></i> Add an Item
        </button>
        <hr/>
    </div>
}