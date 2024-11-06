import InvoiceInformation from "../../Components/Invoice/InvoiceInformation.jsx";
import InvoiceCart from "../../Components/Invoice/InvoiceCart.jsx";
import BaseLayout from "../BasyLayout.jsx";
import {InvoiceProvider} from "../../Contexts/InvoiceContext.jsx";

export default function InvoiceCreate() {
    return <BaseLayout>
        <div className="container-fluid">
            <div className="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 className="h3 mb-0 text-gray-800"><i className="fas fa-file-invoice"></i> Invoice</h1>
                <span className="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                    <a className="text-white">
                        <i className="fas fa-file-invoice fa-sm text-white-50"></i> Invoice / Add
                    </a>
                </span>
            </div>

            <div className="row">
                <InvoiceProvider>
                    <InvoiceInformation/>

                    <InvoiceCart/>
                </InvoiceProvider>
            </div>
        </div>
    </BaseLayout>
}
