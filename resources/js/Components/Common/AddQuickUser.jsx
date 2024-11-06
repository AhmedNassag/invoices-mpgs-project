import {useState} from "react";
import {toast} from "react-toastify";

export default function AddQuickUser({setSelectedUserOption, handleFormDataUpdate}) {
    const [quickUser, setQuickUser] = useState({});
    const [userErrors, setUserErrors] = useState({})

    const handleQuickUser = (key, value) => {
        setQuickUser(formData => ({
            ...formData, [key]: value,
        }));
    }

    const addQuickUser = async (e) => {
        e.preventDefault();
        setUserErrors({});

        try {
            const response = await fetch(route('ajax.quick.user'), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-Inertia': 'true',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(quickUser)
            });

            const result = await response.json();

            if (response?.status === 200) {
                setSelectedUserOption(result);
                handleFormDataUpdate('user_id', result.value);

                jQuery('#addQuickUser').modal('toggle');
            } else {
                if (result.errors) {
                    let resultErrors = result.errors;

                    let errorObj = {};
                    for (let field in resultErrors) {
                        if (resultErrors.hasOwnProperty(field)) {
                            errorObj[field] = resultErrors[field][0];
                        }
                    }

                    setUserErrors((prevState) => {
                        return {...prevState, ...errorObj}
                    });
                } else {
                    toast.error(result?.message || "Something went wrong!");
                }
            }

        } catch (error) {
            handleFormDataUpdate('user_id', null);
            setSelectedUserOption({label: 'Please select', value: ''});

            toast.error("Something went wrong!");

            console.error('There has been a problem with your fetch operation:', error);
        }
    }

    return <div className="modal fade" id="addQuickUser" tabIndex="-1" role="dialog"
                aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div className="modal-dialog modal-lg" role="document">
            <div className="modal-content">
                <div className="modal-header">
                    <h5 className="modal-title">Add New Customer</h5>
                    <button type="button" className="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div className="modal-body">
                    <div className="row">
                        <div className="form-group col-md-6">
                            <label>Name</label> <span className="text-danger">*</span>
                            <input
                                type="text"
                                name="name"
                                className={`form-control ${userErrors?.name ? 'border-danger' : ''}`}
                                onChange={(e) => {
                                    handleQuickUser('name', e.target.value);
                                }}
                            />
                            {userErrors?.name && <p className="text-danger">{userErrors.name}</p>}
                        </div>

                        <div className="form-group col-md-6">
                            <label>Email</label> <span className="text-danger">*</span>
                            <input
                                type="email"
                                name="email"
                                className={`form-control ${userErrors?.email ? 'border-danger' : ''}`}
                                onChange={(e) => {
                                    handleQuickUser('email', e.target.value);
                                }}
                            />
                            {userErrors?.email && <p className="text-danger">{userErrors.email}</p>}
                        </div>

                        <div className="form-group col-md-6">
                            <label>Phone</label>
                            <input
                                type="text"
                                name="phone"
                                className={`form-control ${userErrors?.phone ? 'border-danger' : ''}`}
                                onChange={(e) => {
                                    handleQuickUser('phone', e.target.value);
                                }}
                            />
                            {userErrors?.phone && <p className="text-danger">{userErrors.phone}</p>}
                        </div>

                        <div className="form-group col-md-6">
                            <label>Address</label>
                            <textarea
                                name="address"
                                cols="30" rows="1"
                                className={`form-control ${userErrors?.address ? 'border-danger' : ''}`}
                                onChange={(e) => {
                                    handleQuickUser('address', e.target.value);
                                }}
                            ></textarea>
                            {userErrors?.address && <p className="text-danger">{userErrors.address}</p>}
                        </div>
                    </div>
                </div>
                <div className="modal-footer justify-content-between">
                    <button type="button" className="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
                    <button type="button" className="btn btn-primary  btn-sm" onClick={addQuickUser}>Add New
                        Customer
                    </button>
                </div>
            </div>
        </div>
    </div>
}