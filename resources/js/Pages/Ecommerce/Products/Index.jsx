import React, { useState, useEffect } from 'react';
import Create from './Create';

export default function ProductList() {
    const [products, setProducts] = useState([]);
    const [showCreateForm, setShowCreateForm] = useState(false);
    const [selectedProduct, setSelectedProduct] = useState(null);

    const handleProductAdded = (newProduct) => {
        setProducts((prev) => [...prev, newProduct]);
        setShowCreateForm(false);
    };

    // Cleanup object URLs to avoid memory leaks
    useEffect(() => {
        return () => {
            products.forEach((product) => {
                product.images.forEach((url) => URL.revokeObjectURL(url));
            });
        };
    }, [products]);

    return (
        <div className="container mx-auto p-4">
            <h1 className="text-2xl font-bold mb-4">Products</h1>

            {!showCreateForm && (
                <button
                    onClick={() => setShowCreateForm(true)}
                    className="mb-6 bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition"
                >
                    Add Product
                </button>
            )}

            {showCreateForm && (
                <Create
                    onProductAdded={handleProductAdded}
                    onCancel={() => setShowCreateForm(false)}
                />
            )}

            {/* Product grid */}
            <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                {products.length === 0 && !showCreateForm && (
                    <p className="text-gray-500">No products added yet.</p>
                )}
                {products.map((product) => (
                    <div
                        key={product.id}
                        onClick={() => setSelectedProduct(product)}
                        className="cursor-pointer border rounded shadow hover:shadow-lg transition p-4"
                    >
                        {/* Show first image or fallback */}
                        {product.images && product.images.length > 0 ? (
                            <img
                                src={product.images[0]}
                                alt={product.name}
                                className="w-full h-48 object-cover rounded mb-2"
                            />
                        ) : (
                            <div className="w-full h-48 bg-gray-200 flex items-center justify-center rounded mb-2">
                                <span className="text-gray-500">No image</span>
                            </div>
                        )}
                        <h3 className="text-lg font-semibold">{product.name}</h3>
                        <p className="text-blue-600 font-bold">${product.price}</p>
                    </div>
                ))}
            </div>

            {/* Modal for product details */}
            {selectedProduct && (
                <div
                    className="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
                    onClick={() => setSelectedProduct(null)}
                >
                    <div
                        className="bg-white rounded p-6 max-w-lg max-h-full overflow-auto"
                        onClick={(e) => e.stopPropagation()}
                    >
                        <h2 className="text-2xl font-bold mb-4">
                            {selectedProduct.name}
                        </h2>
                        <div className="mb-4">
                            <strong>Category:</strong> {selectedProduct.category}
                        </div>
                        <div className="mb-4">
                            <strong>Price:</strong> ${selectedProduct.price}
                        </div>
                        <div className="mb-4">
                            <strong>Stock:</strong> {selectedProduct.stock}
                        </div>
                        <div className="mb-4">
                            <strong>Description:</strong>
                            <p>{selectedProduct.description}</p>
                        </div>
                        <div className="grid grid-cols-2 gap-2">
                            {selectedProduct.images.map((img, i) => (
                                <img
                                    key={i}
                                    src={img}
                                    alt={`${selectedProduct.name} ${i + 1}`}
                                    className="w-full h-32 object-cover rounded"
                                />
                            ))}
                        </div>
                        <button
                            className="mt-4 bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition"
                            onClick={() => setSelectedProduct(null)}
                        >
                            Close
                        </button>
                    </div>
                </div>
            )}
        </div>
    );
}
