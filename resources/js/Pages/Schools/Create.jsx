import React from 'react';
import { Head, useForm } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Card, CardBody, CardHeader } from '@/Components/Card';
import { FaSchool, FaArrowLeft } from 'react-icons/fa';

export default function Create({ auth }) {
    const { data, setData, post, processing, errors } = useForm({
        name: '',
        code: '',
        description: '',
        principal_name: '',
        email: '',
        phone: '',
        address: '',
        city: '',
        state: '',
        country: '',
        postal_code: '',
        type: '',
        capacity: '',
        status: 'active',
        logo: null
    });

    const handleSubmit = (e) => {
        e.preventDefault();
        post(route('schools.store'));
    };

    const handleFileChange = (e) => {
        setData('logo', e.target.files[0]);
    };

    return (
        <AuthenticatedLayout
            user={auth.user}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Schools</h2>}
        >
            <Head title="Add New School" />

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <Card>
                        <CardHeader>
                            <div className="flex justify-between items-center">
                                <div className="flex items-center">
                                    <FaSchool className="h-6 w-6 text-indigo-600 mr-3" />
                                    <div>
                                        <h4 className="text-lg font-medium text-gray-900">Add New Institution</h4>
                                        <p className="text-sm text-gray-500">Create a new school record</p>
                                    </div>
                                </div>
                                <a href={route('schools.index')} className="btn btn-light">
                                    <FaArrowLeft className="mr-2" />
                                    Back to List
                                </a>
                            </div>
                        </CardHeader>

                        <CardBody>
                            <form onSubmit={handleSubmit} className="space-y-6">
                                <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    {/* Basic Information */}
                                    <div className="col-span-2">
                                        <h5 className="text-lg font-medium text-gray-900 mb-4">Basic Information</h5>
                                    </div>

                                    <div>
                                        <label htmlFor="name" className="form-label">
                                            Institution Name <span className="text-red-500">*</span>
                                        </label>
                                        <input
                                            type="text"
                                            id="name"
                                            className={`form-control ${errors.name ? 'is-invalid' : ''}`}
                                            value={data.name}
                                            onChange={e => setData('name', e.target.value)}
                                            required
                                        />
                                        {errors.name && <div className="invalid-feedback">{errors.name}</div>}
                                    </div>

                                    <div>
                                        <label htmlFor="code" className="form-label">
                                            School Code <span className="text-red-500">*</span>
                                        </label>
                                        <input
                                            type="text"
                                            id="code"
                                            className={`form-control ${errors.code ? 'is-invalid' : ''}`}
                                            value={data.code}
                                            onChange={e => setData('code', e.target.value)}
                                            required
                                        />
                                        {errors.code && <div className="invalid-feedback">{errors.code}</div>}
                                    </div>

                                    <div className="col-span-2">
                                        <label htmlFor="description" className="form-label">Description</label>
                                        <textarea
                                            id="description"
                                            className={`form-control ${errors.description ? 'is-invalid' : ''}`}
                                            value={data.description}
                                            onChange={e => setData('description', e.target.value)}
                                            rows="3"
                                        />
                                        {errors.description && <div className="invalid-feedback">{errors.description}</div>}
                                    </div>

                                    {/* Contact Information */}
                                    <div className="col-span-2">
                                        <h5 className="text-lg font-medium text-gray-900 mb-4">Contact Information</h5>
                                    </div>

                                    <div>
                                        <label htmlFor="principal_name" className="form-label">
                                            Principal's Name <span className="text-red-500">*</span>
                                        </label>
                                        <input
                                            type="text"
                                            id="principal_name"
                                            className={`form-control ${errors.principal_name ? 'is-invalid' : ''}`}
                                            value={data.principal_name}
                                            onChange={e => setData('principal_name', e.target.value)}
                                            required
                                        />
                                        {errors.principal_name && <div className="invalid-feedback">{errors.principal_name}</div>}
                                    </div>

                                    <div>
                                        <label htmlFor="email" className="form-label">
                                            Email Address <span className="text-red-500">*</span>
                                        </label>
                                        <input
                                            type="email"
                                            id="email"
                                            className={`form-control ${errors.email ? 'is-invalid' : ''}`}
                                            value={data.email}
                                            onChange={e => setData('email', e.target.value)}
                                            required
                                        />
                                        {errors.email && <div className="invalid-feedback">{errors.email}</div>}
                                    </div>

                                    <div>
                                        <label htmlFor="phone" className="form-label">
                                            Phone Number <span className="text-red-500">*</span>
                                        </label>
                                        <input
                                            type="tel"
                                            id="phone"
                                            className={`form-control ${errors.phone ? 'is-invalid' : ''}`}
                                            value={data.phone}
                                            onChange={e => setData('phone', e.target.value)}
                                            required
                                        />
                                        {errors.phone && <div className="invalid-feedback">{errors.phone}</div>}
                                    </div>

                                    <div className="col-span-2">
                                        <label htmlFor="address" className="form-label">
                                            Address <span className="text-red-500">*</span>
                                        </label>
                                        <textarea
                                            id="address"
                                            className={`form-control ${errors.address ? 'is-invalid' : ''}`}
                                            value={data.address}
                                            onChange={e => setData('address', e.target.value)}
                                            rows="2"
                                            required
                                        />
                                        {errors.address && <div className="invalid-feedback">{errors.address}</div>}
                                    </div>

                                    <div>
                                        <label htmlFor="city" className="form-label">
                                            City <span className="text-red-500">*</span>
                                        </label>
                                        <input
                                            type="text"
                                            id="city"
                                            className={`form-control ${errors.city ? 'is-invalid' : ''}`}
                                            value={data.city}
                                            onChange={e => setData('city', e.target.value)}
                                            required
                                        />
                                        {errors.city && <div className="invalid-feedback">{errors.city}</div>}
                                    </div>

                                    <div>
                                        <label htmlFor="state" className="form-label">
                                            State/Province <span className="text-red-500">*</span>
                                        </label>
                                        <input
                                            type="text"
                                            id="state"
                                            className={`form-control ${errors.state ? 'is-invalid' : ''}`}
                                            value={data.state}
                                            onChange={e => setData('state', e.target.value)}
                                            required
                                        />
                                        {errors.state && <div className="invalid-feedback">{errors.state}</div>}
                                    </div>

                                    <div>
                                        <label htmlFor="country" className="form-label">
                                            Country <span className="text-red-500">*</span>
                                        </label>
                                        <input
                                            type="text"
                                            id="country"
                                            className={`form-control ${errors.country ? 'is-invalid' : ''}`}
                                            value={data.country}
                                            onChange={e => setData('country', e.target.value)}
                                            required
                                        />
                                        {errors.country && <div className="invalid-feedback">{errors.country}</div>}
                                    </div>

                                    <div>
                                        <label htmlFor="postal_code" className="form-label">
                                            Postal/ZIP Code <span className="text-red-500">*</span>
                                        </label>
                                        <input
                                            type="text"
                                            id="postal_code"
                                            className={`form-control ${errors.postal_code ? 'is-invalid' : ''}`}
                                            value={data.postal_code}
                                            onChange={e => setData('postal_code', e.target.value)}
                                            required
                                        />
                                        {errors.postal_code && <div className="invalid-feedback">{errors.postal_code}</div>}
                                    </div>

                                    {/* Additional Information */}
                                    <div className="col-span-2">
                                        <h5 className="text-lg font-medium text-gray-900 mb-4">Additional Information</h5>
                                    </div>

                                    <div>
                                        <label htmlFor="type" className="form-label">
                                            School Type <span className="text-red-500">*</span>
                                        </label>
                                        <select
                                            id="type"
                                            className={`form-select ${errors.type ? 'is-invalid' : ''}`}
                                            value={data.type}
                                            onChange={e => setData('type', e.target.value)}
                                            required
                                        >
                                            <option value="">Select Type</option>
                                            <option value="public">Public</option>
                                            <option value="private">Private</option>
                                            <option value="charter">Charter</option>
                                        </select>
                                        {errors.type && <div className="invalid-feedback">{errors.type}</div>}
                                    </div>

                                    <div>
                                        <label htmlFor="capacity" className="form-label">
                                            Student Capacity <span className="text-red-500">*</span>
                                        </label>
                                        <input
                                            type="number"
                                            id="capacity"
                                            className={`form-control ${errors.capacity ? 'is-invalid' : ''}`}
                                            value={data.capacity}
                                            onChange={e => setData('capacity', e.target.value)}
                                            min="1"
                                            required
                                        />
                                        {errors.capacity && <div className="invalid-feedback">{errors.capacity}</div>}
                                    </div>

                                    <div>
                                        <label htmlFor="status" className="form-label">
                                            Status <span className="text-red-500">*</span>
                                        </label>
                                        <select
                                            id="status"
                                            className={`form-select ${errors.status ? 'is-invalid' : ''}`}
                                            value={data.status}
                                            onChange={e => setData('status', e.target.value)}
                                            required
                                        >
                                            <option value="active">Active</option>
                                            <option value="inactive">Inactive</option>
                                        </select>
                                        {errors.status && <div className="invalid-feedback">{errors.status}</div>}
                                    </div>

                                    <div>
                                        <label htmlFor="logo" className="form-label">School Logo</label>
                                        <input
                                            type="file"
                                            id="logo"
                                            className={`form-control ${errors.logo ? 'is-invalid' : ''}`}
                                            onChange={handleFileChange}
                                            accept="image/*"
                                        />
                                        {errors.logo && <div className="invalid-feedback">{errors.logo}</div>}
                                    </div>
                                </div>

                                <div className="flex justify-end gap-4">
                                    <a href={route('schools.index')} className="btn btn-light">
                                        Cancel
                                    </a>
                                    <button
                                        type="submit"
                                        className="btn btn-primary"
                                        disabled={processing}
                                    >
                                        Create School
                                    </button>
                                </div>
                            </form>
                        </CardBody>
                    </Card>
                </div>
            </div>
        </AuthenticatedLayout>
    );
} 