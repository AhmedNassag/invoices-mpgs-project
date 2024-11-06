import TaxList from "./Tax/TaxList.jsx";
import {useInvoice} from "../../Contexts/InvoiceContext.jsx";

export default function InvoiceCartSummary() {
    const {invoiceSubtotal, discountAmount, setDiscountAmount, deliveryCharge, setDeliveryCharge, invoiceTotalAmount} = useInvoice();

    return (
        <div className="col-md-6 offset-md-6">
            <div className="invoice-summary">
                <div className="form-group row">
                    <label htmlFor="staticEmail" className="col-sm-6 col-form-label">Subtotal</label>
                    <div className="col-sm-6">
                        <span className="form-control-plaintext totalSubtotal font-weight-bold">{invoiceSubtotal.toFixed(2)}</span>
                    </div>
                </div>

                <div className="form-group row">
                    <label htmlFor="inputPassword" className="col-sm-6 col-form-label">Discount</label>
                    <div className="col-sm-6">
                        <input type="number" min="0" value={discountAmount} onChange={(e) => {
                            setDiscountAmount(e.target.value)
                        }} className="form-control form-control-sm"/>
                    </div>
                </div>

                <div className="form-group row">
                    <label htmlFor="inputPassword" className="col-sm-6 col-form-label">Delivery Charge</label>
                    <div className="col-sm-6">
                        <input type="number" min="0" value={deliveryCharge} onChange={(e) => {
                            setDeliveryCharge(e.target.value)
                        }} className="form-control form-control-sm"/>
                    </div>
                </div>

                <TaxList/>

                <hr/>
                <div className="form-group row">
                    <label htmlFor="staticEmail" className="col-sm-6 col-form-label">Total Amount</label>
                    <div className="col-sm-6">
                        <span className="form-control-plaintext">{invoiceTotalAmount.toFixed(2)}</span>
                    </div>
                </div>
            </div>
        </div>

    )
}