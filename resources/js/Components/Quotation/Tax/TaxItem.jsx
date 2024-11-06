import Select from "react-select";
import {smallUiReactSelect} from "../../../Utils/Css.js";
import {usePage} from "@inertiajs/react";
import {useQuotation} from "../../../Contexts/QuotationContext.jsx";
import {useEffect, useState} from "react";

export default function TaxItem({index, tax}) {

    const {taxRates, quotation, isEditPage} = usePage().props;
    const {updateQuotationTax, removeQuotationTax} = useQuotation();
    const [selectedTaxOption, setSelectedTaxOption] = useState({label: 'Please select', value: ''});

    useEffect(() => {
        if (quotation && isEditPage) {
            const selectedTax = taxRates.find((rate) => {
                return rate.id === tax.tax_rate_id;
            });

            if (selectedTax) {
                setSelectedTaxOption((prevState) => {
                    return {...prevState, value: selectedTax.id, label: selectedTax.name}
                });
            }
        }
    }, [quotation, taxRates, tax]);

    return <div className="form-group row">
        <div className="col-sm-6">
            <Select
                name="product_id"
                styles={smallUiReactSelect}
                key={Math.random()}
                defaultValue={selectedTaxOption}
                options={taxRates.map((tax) => {
                    return {label: tax?.name_with_percent, value: tax.id}
                })}
                onChange={(taxOption) => {
                    const selectedTaxItem = taxRates.find((pItem) => {
                        return pItem.id === taxOption?.value;
                    });

                    if (selectedTaxItem) {
                        setSelectedTaxOption((prevState) => {
                            return {...prevState, value: selectedTaxItem.id, label: selectedTaxItem.name}
                        });

                        updateQuotationTax(index, {
                            'tax_rate_id': selectedTaxItem.id,
                            'percent': selectedTaxItem.percent
                        });
                    }
                }}
            />
        </div>
        <div className="col-sm-6">
            <div className="input-group">
                <input type="text" value={tax.amount} className="form-control form-control-sm" readOnly/>
                <div className="input-group-append">
                    <button
                        className="btn btn-sm btn-danger"
                        onClick={() => removeQuotationTax(index)}
                    >
                        <i className="fa fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
}