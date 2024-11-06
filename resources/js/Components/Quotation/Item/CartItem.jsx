import Select from "react-select";
import {smallUiReactSelect} from "../../../Utils/Css.js";
import {usePage} from "@inertiajs/react";
import {useQuotation} from "../../../Contexts/QuotationContext.jsx";
import {useEffect, useState} from "react";

export default function CartItem({index, item}) {
    const {products, quotation, isEditPage} = usePage().props;
    const {updateQuotationItem, removeQuotationItem} = useQuotation();

    const [selectedProductOption, setSelectedProductOption] = useState({label: 'Please select', value: ''});

    useEffect(() => {
        if (quotation && isEditPage) {
            const selectedProduct = products.find((product) => {
                return product.id === item.product_id;
            });

            if (selectedProduct) {
                setSelectedProductOption((prevState) => {
                    return {...prevState, value: selectedProduct.id, label: selectedProduct.name}
                });
            }
        }
    }, [quotation, products, item]);

    return (
        <tr className="d-flex">
            <td className="col-5">
                <Select
                    styles={smallUiReactSelect}
                    key={Math.random()}
                    defaultValue={selectedProductOption}
                    options={products.map((product) => {
                        return {label: product.name, value: product.id}
                    })}
                    onChange={(selectedProductOption) => {
                        const selectedProductItem = products.find((pItem) => {
                            return pItem.id === selectedProductOption?.value;
                        });

                        if (selectedProductItem) {
                            setSelectedProductOption((prevState) => {
                                return {...prevState, value: selectedProductItem.id, label: selectedProductItem.name}
                            });

                            updateQuotationItem(index, {
                                'product_id': selectedProductItem.id,
                                'unit_price': selectedProductItem.price
                            });
                        }
                    }}
                />
            </td>
            <td className="col-2">
                <input type="number" min="1" value={item.quantity} onChange={(e) => {
                    updateQuotationItem(index, {'quantity': parseInt(e.target.value || 1)});
                }} className="form-control form-control-sm"/>
            </td>
            <td className="col-2">{item.unit_price.toFixed(2)}</td>
            <td className="col-2">{(item.quantity * item.unit_price).toFixed(2)}</td>
            <td className="col-1">
                <button className="btn btn-sm btn-danger" onClick={() => removeQuotationItem(index)}>
                    <i className="fa fa-trash"></i>
                </button>
            </td>
        </tr>
    )
}