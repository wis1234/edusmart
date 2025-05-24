import React from 'react';
import { useForm } from '@inertiajs/react';

export default function Create({ onProductAdded, onCancel }) {
    const { data, setData, post, processing, errors, reset } = useForm({
        name: '',
        description: '',
        price: '',
        stock: '',
        category: '',
        images: [],
    });

    const handleFileChange = (e) => {
        setData('images', Array.from(e.target.files));
    };

    const handleSubmit = (e) => {
        e.preventDefault();

        const previewUrls = data.images?.map((file) => URL.createObjectURL(file)) || [];

        post('/ecommerce/products', {
            forceFormData: true,
            onSuccess: () => {
                onProductAdded({
                    id: Date.now(), // or some unique id
                    name: data.name,
                    description: data.description,
                    price: data.price,
                    stock: data.stock,
                    category: data.category,
                    images: previewUrls,
                });
                reset();
            },
        });
    };

    return (
        <div className="max-w-md mx-auto p-4 border rounded shadow">
            <h2 className="text-xl font-bold mb-4">Add New Product</h2>
            <form onSubmit={handleSubmit} encType="multipart/form-data" className="space-y-4">
                <div>
                    <label className="block mb-1 font-semibold">Name</label>
                    <input
                        type="text"
                        value={data.name}
                        onChange={(e) => setData('name', e.target.value)}
                        className="w-full border px-3 py-2 rounded"
                    />
                    {errors.name && <div className="text-red-600">{errors.name}</div>}
                </div>

                <div>
                    <label className="block mb-1 font-semibold">Description</label>
                    <textarea
                        value={data.description}
                        onChange={(e) => setData('description', e.target.value)}
                        className="w-full border px-3 py-2 rounded"
                    />
                    {errors.description && <div className="text-red-600">{errors.description}</div>}
                </div>

                <div>
                    <label className="block mb-1 font-semibold">Price</label>
                    <input
                        type="number"
                        step="0.01"
                        value={data.price}
                        onChange={(e) => setData('price', e.target.value)}
                        className="w-full border px-3 py-2 rounded"
                    />
                    {errors.price && <div className="text-red-600">{errors.price}</div>}
                </div>

                <div>
                    <label className="block mb-1 font-semibold">Stock</label>
                    <input
                        type="number"
                        value={data.stock}
                        onChange={(e) => setData('stock', e.target.value)}
                        className="w-full border px-3 py-2 rounded"
                    />
                    {errors.stock && <div className="text-red-600">{errors.stock}</div>}
                </div>

                <div>
                    <label className="block mb-1 font-semibold">Category</label>
                    <input
                        type="text"
                        value={data.category}
                        onChange={(e) => setData('category', e.target.value)}
                        className="w-full border px-3 py-2 rounded"
                    />
                    {errors.category && <div className="text-red-600">{errors.category}</div>}
                </div>

                <div>
                    <label className="block mb-1 font-semibold">Product Images (Multiple)</label>
                    <input
                        type="file"
                        multiple
                        onChange={handleFileChange}
                        className="w-full border px-3 py-2 rounded"
                    />
                    {errors.images && <div className="text-red-600">{errors.images}</div>}
                </div>

                <div className="flex space-x-4">
                    <button
                        type="submit"
                        disabled={processing}
                        className="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition"
                    >
                        {processing ? 'Saving...' : 'Save'}
                    </button>
                    <button
                        type="button"
                        onClick={onCancel}
                        className="bg-gray-400 text-white px-4 py-2 rounded hover:bg-gray-500 transition"
                    >
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    );
}
