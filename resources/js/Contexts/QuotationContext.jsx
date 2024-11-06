import React, { createContext, useState, useEffect, useContext } from 'react';
import {usePage} from "@inertiajs/react";

// Create a context
const QuotationContext = createContext(null);

// Custom hook to use the QuotationContext
export const useQuotation = () => useContext(QuotationContext);

export const QuotationProvider = ({children}) => {
    const {isEditPage, quotation, taxRates} = usePage().props;

    const [items, setItems] = useState([{product_id: '', quantity: 1, unit_price: 0}]);
    const [taxes, setTaxes] = useState([]);
    const [discountAmount, setDiscountAmount] = useState(0);
    const [deliveryCharge, setDeliveryCharge] = useState(0);
    const [quotationQuantity, setQuotationQuantity] = useState(0);
    const [quotationSubtotal, setQuotationSubtotal] = useState(0);
    const [quotationTotalAmount, setQuotationTotalAmount] = useState(0);
    const [forceCalculate, setForceCalculate] = useState(0);

    // For Quotation Item
    const addQuotationItem = () => {
        setItems([...items, {product_id: '', quantity: 1, unit_price: 0}]);
    }

    const updateQuotationItem = (updateIndex, obj) => {
        setItems((preItems) => {
            return preItems.map((item, index) => {
                return index === updateIndex ? {...item, ...obj} : item
            });
        });
    }

    const removeQuotationItem = (indexToRemove) => {
        let newItems = items.filter((_, index) => {
            return indexToRemove !== index;
        });

        setItems(newItems);
    }

    // For Quotation Tax
    const addQuotationTax = () => {
        setTaxes([...taxes, {tax_rate_id: '', percent: 0, amount: 0.00}]);
    }

    const updateQuotationTax = (updateIndex, obj) => {
        setTaxes((preItems) => {
            return preItems.map((item, index) => {
                return index === updateIndex ? {...item, ...obj} : item
            });
        });

        setForceCalculate(prevForceCalculate => prevForceCalculate + 1);
    }

    const removeQuotationTax = (indexToRemove) => {
        let newTaxes = taxes.filter((_, index) => {
            return indexToRemove !== index;
        });

        setTaxes(newTaxes);
    }


    useEffect(() => {
        let quotationQty = 0;
        let quotationSubAmount = 0;

        items.forEach(item => {
            quotationQty += item.quantity;
            quotationSubAmount += parseFloat(item.unit_price) * item.quantity;
        });

        setQuotationQuantity(quotationQty);
        setQuotationSubtotal(quotationSubAmount);

        // Update taxes
        setTaxes(prevTaxes =>
            prevTaxes.map(tax => ({
                ...tax,
                amount: ((tax.percent / 100) * quotationSubAmount).toFixed(2)
            }))
        );
    }, [items, forceCalculate]);

    useEffect(() => {
        // Recalculate total amount whenever subtotal, taxes, discount, or delivery charge change
        const totalTaxAmount = taxes.reduce((sum, tax) => sum + parseFloat(tax.amount), 0);

        const totalAmount = (quotationSubtotal + parseFloat(deliveryCharge.toString()) + totalTaxAmount) - parseFloat(discountAmount.toString());

        setQuotationTotalAmount(totalAmount);

    }, [taxes, quotationSubtotal, deliveryCharge, discountAmount]);

    useEffect(() => {
        if(isEditPage) {
            const {products, taxes} = quotation || {};

            if(products?.length > 0 ) {
                setItems((prevItems) => {
                    return products.map((product) => {
                        return { product_id: product.product_id, quantity: product.quantity, unit_price: product.unit_price}
                    });
                });
            }

            if(taxes?.length > 0 ) {
                setTaxes((preTaxes) => {
                    return taxes.map((tax) => {
                        const taxRate = taxRates.find((rate) => {
                            return rate.id === tax.tax_rate_id;
                        });

                        return {tax_rate_id: tax.tax_rate_id, percent: taxRate?.percent || 0, amount: tax.amount}
                    });
                })
            }

            setDiscountAmount(quotation?.discount_amount || 0);
            setDeliveryCharge(quotation?.delivery_charge || 0);
        }
    }, []);

    return (
        <QuotationContext.Provider value={{
            items,
            setItems,
            taxes,
            setTaxes,
            discountAmount,
            setDiscountAmount,
            deliveryCharge,
            setDeliveryCharge,
            quotationQuantity,
            setQuotationQuantity,
            quotationSubtotal,
            setQuotationSubtotal,
            quotationTotalAmount,
            setQuotationTotalAmount,
            forceCalculate,
            setForceCalculate,
            addQuotationItem,
            updateQuotationItem,
            removeQuotationItem,
            addQuotationTax,
            updateQuotationTax,
            removeQuotationTax
        }} >
            {children}
        </QuotationContext.Provider>
    );
};