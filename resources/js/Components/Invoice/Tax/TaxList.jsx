import TaxItem from "./TaxItem.jsx";
import {useInvoice} from "../../../Contexts/InvoiceContext.jsx";

export default function TaxList() {
    const {taxes, addInvoiceTax} = useInvoice();

    return <>
        {taxes.length > 0 && <div>
            {taxes.map((tax, index) => {
                return <TaxItem key={index} index={index} tax={tax}></TaxItem>
            })}
        </div>}

        <hr/>

        <div className="form-group row">
            <div className="col-sm-12">
                <button className="btn btn-primary btn-sm btn-block" onClick={addInvoiceTax}>
                    <i className="fa fa-plus"></i> Add Tax
                </button>
            </div>
        </div>
    </>
}