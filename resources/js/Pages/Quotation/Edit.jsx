import QuotationInformation from "../../Components/Quotation/QuotationInformation.jsx";
import QuotationCart from "../../Components/Quotation/QuotationCart.jsx";
import BaseLayout from "../BasyLayout.jsx";
import {QuotationProvider} from "../../Contexts/QuotationContext.jsx";

export default function QuotationCreate() {
    return <BaseLayout>
        <div className="container-fluid">
            <div className="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 className="h3 mb-0 text-gray-800"><i className="fas fa-file-invoice"></i> Quotation</h1>
                <span className="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                    <a className="text-white">
                        <i className="fas fa-file-invoice fa-sm text-white-50"></i> Quotation / Edit
                    </a>
                </span>
            </div>

            <div className="row">
                <QuotationProvider>
                    <QuotationInformation/>

                    <QuotationCart/>
                </QuotationProvider>
            </div>
        </div>
    </BaseLayout>
}
