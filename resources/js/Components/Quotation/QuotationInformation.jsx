import Select from 'react-select';
import DatePicker from "react-datepicker";
import {useEffect, useState} from "react";
import "react-datepicker/dist/react-datepicker.css";
import {router, usePage} from "@inertiajs/react";
import {toast} from "react-toastify";
import {useQuotation} from "../../Contexts/QuotationContext.jsx";
import AddQuickUser from "../Common/AddQuickUser.jsx";

export default function QuotationInformation() {
    const {users, isEditPage, quotation} = usePage().props;

    const {items, taxes, discountAmount, deliveryCharge} = useQuotation();

    const [formData, setFormData] = useState({
        user_id: null,
        date: new Date(),
        due_date: new Date(),
        reference_no: '',
        file: '',
        note: '',
        items,
        taxes,
        discount_amount: discountAmount,
        delivery_charge: deliveryCharge
    });

    const {errors} = usePage().props

    const [selectedUserOption, setSelectedUserOption] = useState({label: 'Please select', value: ''});
    const [fileName, setFileName] = useState('Choose file');

    const saveQuotation = (e) => {
        e.preventDefault();

        router.post('/quotation', formData, {
            onSuccess: () => {
                toast.success("The quotation added successfully");
            }
        });
    }

    const updateQuotation = (e) => {
        e.preventDefault();

        router.put('/quotation/' + quotation.id, formData, {
            onSuccess: () => {
                toast.success("The quotation update successfully");
            }
        });
    }

    const handleFormDataUpdate = (key, value) => {
        setFormData(formData => ({
            ...formData, [key]: value,
        }));
    }

    useEffect(() => {
        if(quotation) {
            setFormData((prevState) => {
                return {
                    ...prevState,
                    user_id: quotation.user_id,
                    date: quotation.date,
                    due_date: quotation.due_date,
                    reference_no: quotation.reference_no || '',
                    note: quotation.note || ''
                }
            });

            const selectedUser = users.find((user) => {
                return user.value === quotation.user_id;
            });

            setSelectedUserOption((prevState) => {
                return {...prevState, ...selectedUser}
            });
        }
    }, []);

    useEffect(() => {
        if (Object.keys(errors).length > 0) {
            toast.error(Object.values(errors)[0]);
        }
    }, [errors]);

    useEffect(() => {
        setFormData((prevState) => {
            return {...prevState, ...{items, taxes, discount_amount: discountAmount, delivery_charge: deliveryCharge}};
        });
    }, [items, taxes, discountAmount, deliveryCharge]);

    return <div className="col-xl-3 col-md-3">
        <div className="card shadow">
            <div className="card-body">
                <div className="row">
                    <div className="col-sm-12">
                        <div className="form-group">
                            <div className="d-flex justify-content-between mb-2 align-content-center">
                                <label>User <span className="text-danger">*</span></label>

                                <button data-toggle="modal" data-target="#addQuickUser" type="button"
                                        className="btn btn-primary btn-sm">
                                    <i className="fas fa-plus"></i> Quick Customer
                                </button>
                            </div>

                            <Select
                                key={selectedUserOption?.value}
                                className={errors?.user_id ? 'border border-danger rounded' : ''}
                                name="user_id"
                                defaultValue={selectedUserOption}
                                options={users}
                                onChange={(user) => {
                                    handleFormDataUpdate('user_id', user?.value);
                                }}
                            />
                            {errors?.user_id && <p className="text-danger">{errors.user_id}</p>}
                        </div>

                        <div className="form-group">
                            <label>Date</label> <span className="text-danger">*</span> <br/>
                            <DatePicker
                                className={errors?.date ? 'form-control border border-danger rounded' : 'form-control'}
                                selected={formData.date}
                                dateFormat="dd-MM-yyyy"
                                onChange={(date) => {
                                    handleFormDataUpdate('date', date);
                                }}
                            />
                            {errors?.date && <p className="text-danger">{errors.date}</p>}
                        </div>

                        <div className="form-group">
                            <label>Due Date</label> <span className="text-danger">*</span> <br/>
                            <DatePicker
                                className={errors?.due_date ? 'form-control border border-danger rounded' : 'form-control'}
                                selected={formData.due_date}
                                dateFormat="dd-MM-yyyy"
                                onChange={(due_date) => {
                                    handleFormDataUpdate('due_date', due_date);
                                }}
                            />
                            {errors?.due_date && <p className="text-danger">{errors.due_date}</p>}
                        </div>

                        <div className="form-group">
                            <label>Reference no</label>
                            <input
                                type="text"
                                name="reference_no"
                                className="form-control"
                                value={formData.reference_no}
                                onChange={(e) => {
                                    handleFormDataUpdate('reference_no', e.target.value);
                                }}
                            />
                            {errors?.reference_no && <p className="text-danger">{errors.reference_no}</p>}
                        </div>

                        <div className="form-group">
                            <label>File</label>
                            <div className="custom-file">
                                <input id="file" name="file" type="file" className="custom-file-input"
                                   onChange={(e) => {
                                       if (e.target.files[0]) {
                                           handleFormDataUpdate('file', e.target.files[0]);
                                           setFileName(e.target.files[0].name);
                                       }
                                   }} />
                                <label className="custom-file-label" htmlFor="file">{fileName}</label>
                            </div>
                            {errors?.file && <p className="text-danger">{errors.file}</p>}
                        </div>

                        <div className="form-group">
                            <label>Note</label>
                            <textarea
                                name="note" cols="30" rows="3"
                                value={formData.note}
                                className="form-control"
                                onChange={(e) => {
                                    handleFormDataUpdate('note', e.target.value);
                                }}
                            ></textarea>
                            {errors?.note && <p className="text-danger">{errors.note}</p>}
                        </div>
                    </div>
                </div>
            </div>

            <div className="card-footer">
                <button type="submit" className="btn btn-primary btn-block" onClick={isEditPage ? updateQuotation : saveQuotation}>
                    {isEditPage ? 'Update' : 'Create'} Quotation
                </button>
            </div>

            <AddQuickUser
                setSelectedUserOption={setSelectedUserOption}
                handleFormDataUpdate={handleFormDataUpdate}
            >
            </AddQuickUser>
        </div>
    </div>
}