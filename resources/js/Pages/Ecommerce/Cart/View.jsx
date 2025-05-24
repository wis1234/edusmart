import React from 'react';
import { Link } from '@inertiajs/react';

export default function CartView({ cart }) {
    const cartItems = Object.values(cart);

    const totalPrice = cartItems.reduce((total, item) => {
        return total + item.product.price * item.quantity;
    }, 0);

    return (
        <div className="container mx-auto p-4">
            <h1 className="text-2xl font-bold mb-4">Shopping Cart</h1>
            {cartItems.length === 0 ? (
                <p>Your cart is empty.</p>
            ) : (
                <>
                    <table className="min-w-full bg-white mb-4">
                        <thead>
                            <tr>
                                <th className="py-2 px-4 border-b">Product</th>
                                <th className="py-2 px-4 border-b">Price</th>
                                <th className="py-2 px-4 border-b">Quantity</th>
                                <th className="py-2 px-4 border-b">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            {cartItems.map(({ product, quantity }) => (
                                <tr key={product.id}>
                                    <td className="py-2 px-4 border-b">{product.name}</td>
                                    <td className="py-2 px-4 border-b">${product.price}</td>
                                    <td className="py-2 px-4 border-b">{quantity}</td>
                                    <td className="py-2 px-4 border-b">${(product.price * quantity).toFixed(2)}</td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                    <div className="text-right font-bold mb-4">Total: ${totalPrice.toFixed(2)}</div>
                    <Link href="/ecommerce/checkout" className="bg-green-600 text-white px-4 py-2 rounded">
                        Proceed to Checkout
                    </Link>
                </>
            )}
        </div>
    );
}
