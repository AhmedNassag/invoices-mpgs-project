import InvoiceCartSummary from "./InvoiceCartSummary.jsx";
import CartList from "./Item/CartList.jsx";
import {usePage} from "@inertiajs/react";

export default function InvoiceCart() {
    const {isEditPage} = usePage().props;

    return <div className="col-xl-9 col-md-9">
        <div className="card shadow mb-4">
            <div className="card-header">
                <h2>{isEditPage ? 'Update' : 'Create'} Invoice</h2>
            </div>

            <div className="card-body">
                <div className="row">
                    <CartList/>

                    <InvoiceCartSummary/>
                </div>
            </div>
        </div>
    </div>
}