import React, { createContext, useState, useEffect, useContext } from 'react';
import {usePage} from "@inertiajs/react";

// Create a context
const InvoiceContext = createContext(null);

// Custom hook to use the InvoiceContext
export const useInvoice = () => useContext(InvoiceContext);

export const InvoiceProvider = ({children}) => {
    const {isEditPage, invoice, taxRates} = usePage().props;

    const [items, setItems] = useState([{product_id: '', quantity: 1, unit_price: 0}]);
    const [taxes, setTaxes] = useState([]);
    const [discountAmount, setDiscountAmount] = useState(0);
    const [deliveryCharge, setDeliveryCharge] = useState(0);
    const [invoiceQuantity, setInvoiceQuantity] = useState(0);
    const [invoiceSubtotal, setInvoiceSubtotal] = useState(0);
    const [invoiceTotalAmount, setInvoiceTotalAmount] = useState(0);
    const [forceCalculate, setForceCalculate] = useState(0);

    // For Invoice Item
    const addInvoiceItem = () => {
        setItems([...items, {product_id: '', quantity: 1, unit_price: 0}]);
    }

    const updateInvoiceItem = (updateIndex, obj) => {
        setItems((preItems) => {
            return preItems.map((item, index) => {
                return index === updateIndex ? {...item, ...obj} : item
            });
        });
    }

    const removeInvoiceItem = (indexToRemove) => {
        let newItems = items.filter((_, index) => {
            return indexToRemove !== index;
        });

        setItems(newItems);
    }

    // For Invoice Tax
    const addInvoiceTax = () => {
        setTaxes([...taxes, {tax_rate_id: '', percent: 0, amount: 0.00}]);
    }

    const updateInvoiceTax = (updateIndex, obj) => {
        setTaxes((preItems) => {
            return preItems.map((item, index) => {
                return index === updateIndex ? {...item, ...obj} : item
            });
        });

        setForceCalculate(prevForceCalculate => prevForceCalculate + 1);
    }

    const removeInvoiceTax = (indexToRemove) => {
        let newTaxes = taxes.filter((_, index) => {
            return indexToRemove !== index;
        });

        setTaxes(newTaxes);
    }


    useEffect(() => {
        let invoiceQty = 0;
        let invoiceSubAmount = 0;

        items.forEach(item => {
            invoiceQty += item.quantity;
            invoiceSubAmount += parseFloat(item.unit_price) * item.quantity;
        });

        setInvoiceQuantity(invoiceQty);
        setInvoiceSubtotal(invoiceSubAmount);

        // Update taxes
        setTaxes(prevTaxes =>
            prevTaxes.map(tax => ({
                ...tax,
                amount: ((tax.percent / 100) * invoiceSubAmount).toFixed(2)
            }))
        );
    }, [items, forceCalculate]);

    useEffect(() => {
        // Recalculate total amount whenever subtotal, taxes, discount, or delivery charge change
        const totalTaxAmount = taxes.reduce((sum, tax) => sum + parseFloat(tax.amount), 0);

        const totalAmount = (invoiceSubtotal + parseFloat(deliveryCharge.toString()) + totalTaxAmount) - parseFloat(discountAmount.toString());

        setInvoiceTotalAmount(totalAmount);

    }, [taxes, invoiceSubtotal, deliveryCharge, discountAmount]);

    useEffect(() => {
        if(isEditPage) {
            const {products, taxes} = invoice || {};

            if(products.length > 0 ) {
                setItems((prevItems) => {
                    return products.map((product) => {
                        return { product_id: product.product_id, quantity: product.quantity, unit_price: product.unit_price}
                    });
                });
            }

            if(taxes.length > 0 ) {
                setTaxes((preTaxes) => {
                    return taxes.map((tax) => {
                        const taxRate = taxRates.find((rate) => {
                            return rate.id === tax.tax_rate_id;
                        });

                        return {tax_rate_id: tax.tax_rate_id, percent: taxRate?.percent || 0, amount: tax.amount}
                    });
                })
            }

            setDiscountAmount(invoice?.discount_amount || 0);
            setDeliveryCharge(invoice?.delivery_charge || 0);
        }
    }, []);

    return (
        <InvoiceContext.Provider value={{
            items,
            setItems,
            taxes,
            setTaxes,
            discountAmount,
            setDiscountAmount,
            deliveryCharge,
            setDeliveryCharge,
            invoiceQuantity,
            setInvoiceQuantity,
            invoiceSubtotal,
            setInvoiceSubtotal,
            invoiceTotalAmount,
            setInvoiceTotalAmount,
            forceCalculate,
            setForceCalculate,
            addInvoiceItem,
            updateInvoiceItem,
            removeInvoiceItem,
            addInvoiceTax,
            updateInvoiceTax,
            removeInvoiceTax
        }} >
            {children}
        </InvoiceContext.Provider>
    );
};