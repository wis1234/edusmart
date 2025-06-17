import React from 'react';

const Button = ({
    type = 'button',
    className = '',
    variant = 'primary',
    disabled = false,
    children,
    ...props
}) => {
    const baseClasses = 'inline-flex items-center px-4 py-2 border rounded-md font-semibold text-xs uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-offset-2 transition ease-in-out duration-150';
    
    const variants = {
        primary: 'border-transparent text-white bg-blue-600 hover:bg-blue-700 focus:ring-blue-500 disabled:opacity-50',
        secondary: 'border-gray-300 text-gray-700 bg-white hover:bg-gray-50 focus:ring-gray-500 disabled:opacity-50',
        danger: 'border-transparent text-white bg-red-600 hover:bg-red-700 focus:ring-red-500 disabled:opacity-50',
        success: 'border-transparent text-white bg-green-600 hover:bg-green-700 focus:ring-green-500 disabled:opacity-50',
    };

    const variantClasses = variants[variant] || variants.primary;

    return (
        <button
            type={type}
            className={`${baseClasses} ${variantClasses} ${className}`}
            disabled={disabled}
            {...props}
        >
            {children}
        </button>
    );
};

export default Button; 