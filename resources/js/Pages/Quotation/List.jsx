import QuotationHeader from "../../Components/Quotation/List/QuotationHeader.jsx";
import QuotationItem from "../../Components/Quotation/List/QuotationItem.jsx";
import BaseLayout from "../BasyLayout.jsx";
import ReactPaginate from "react-paginate";
import {useState} from "react";
import {usePage} from "@inertiajs/react";

const itemsPerPage = 10;

export default function InvoiceList({quotations}) {
    const {can} = usePage().props;

    const [itemOffset, setItemOffset] = useState(0);

    const endOffset = itemOffset + itemsPerPage;
    const currentItems = quotations.slice(itemOffset, endOffset);
    const pageCount = Math.ceil(quotations.length / itemsPerPage);

    // Invoke when user click to request another page.
    const handlePageClick = (event) => {
        setItemOffset((event.selected * itemsPerPage) % quotations.length);
    };

    return <BaseLayout>
        <div className="container-fluid">
            <div className="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 className="h3 mb-0 text-gray-800"><i className="fas fa-file-invoice"></i> Quotation</h1>
                <span className="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                    <a className="text-white">
                        <i className="fas fa-file-invoice fa-sm text-white-50"></i> Quotation
                    </a>
                </span>
            </div>

            <div className="row">
                <div className="col-xl-12 col-md-12 mb-4">
                    <div className="card shadow mb-4">
                        {can?.quotation_create &&
                            <div className="card-header py-3">
                            <div className="row">
                                <div className="col-sm-6 pull-left">
                                    <h6 className="m-0 font-weight-bold text-primary">
                                        <a href={route('admin.quotation.create')} className="btn btn-primary">
                                            <i className="fa fa-plus"></i> Add Quotation
                                        </a>
                                    </h6>
                                </div>
                            </div>
                        </div>}

                        <div className="card-body">
                            <div className="table-responsive">
                                <table className="table table-bordered" width="100%">
                                    <thead>
                                    <QuotationHeader></QuotationHeader>
                                    </thead>
                                    <tbody>
                                    {currentItems.map((quotation, index) => {
                                        return <QuotationItem key={index} quotation={quotation}></QuotationItem>
                                    })}
                                    </tbody>
                                    <tfoot>
                                    <QuotationHeader></QuotationHeader>
                                    </tfoot>
                                </table>
                            </div>

                            <nav aria-label="Page navigation example">
                                <ReactPaginate
                                    breakLabel="..."
                                    nextLabel="Next >"
                                    onPageChange={handlePageClick}
                                    pageRangeDisplayed={5}
                                    pageCount={pageCount}
                                    previousLabel="< Previous"
                                    renderOnZeroPageCount={null}
                                    containerClassName="pagination font-weight-bold"
                                    className="pagination"
                                    pageClassName='page-item'
                                    pageLinkClassName="page-link"
                                    previousClassName='page-item'
                                    previousLinkClassName="page-link"
                                    nextClassName='page-item'
                                    nextLinkClassName="page-link"
                                    activeClassName="active"
                                />
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </BaseLayout>;
}
