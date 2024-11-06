import TaxItem from "./TaxItem.jsx";
import {useQuotation} from "../../../Contexts/QuotationContext.jsx";

export default function TaxList() {
    const {taxes, addQuotationTax} = useQuotation();

    return <>
        {taxes.length > 0 && <div>
            {taxes.map((tax, index) => {
                return <TaxItem key={index} index={index} tax={tax}></TaxItem>
            })}
        </div>}

        <hr/>

        <div className="form-group row">
            <div className="col-sm-12">
                <button className="btn btn-primary btn-sm btn-block" onClick={addQuotationTax}>
                    <i className="fa fa-plus"></i> Add Tax
                </button>
            </div>
        </div>
    </>
}