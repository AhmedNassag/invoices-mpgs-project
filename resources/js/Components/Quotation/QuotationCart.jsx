import QuotationCartSummary from "./QuotationCartSummary.jsx";
import CartList from "./Item/CartList.jsx";
import {usePage} from "@inertiajs/react";

export default function QuotationCart() {
    const {isEditPage} = usePage().props;

    return <div className="col-xl-9 col-md-9">
        <div className="card shadow mb-4">
            <div className="card-header">
                <h2>{isEditPage ? 'Update' : 'Create'} Quotation</h2>
            </div>

            <div className="card-body">
                <div className="row">
                    <CartList/>

                    <QuotationCartSummary/>
                </div>
            </div>
        </div>
    </div>
}