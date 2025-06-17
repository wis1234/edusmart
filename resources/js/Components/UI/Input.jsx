import React, { forwardRef } from 'react';

const Input = forwardRef(({
    type = 'text',
    label,
    error,
    className = '',
    id,
    leftIcon,
    ...props
}, ref) => {
    const inputId = id || `input-${Math.random().toString(36).substr(2, 9)}`;

    return (
        <div className="space-y-1 relative rounded-md shadow-sm">
            {leftIcon && (
                <div className="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                    {leftIcon}
                </div>
            )}
            {label && (
                <label
                    htmlFor={inputId}
                    className="block text-sm font-medium text-gray-700"
                >
                    {label}
                </label>
            )}
            <input
                id={inputId}
                type={type}
                ref={ref}
                className={`
                    block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 
                    placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6
                    ${leftIcon ? 'pl-10' : 'pl-3'}
                    ${error
                        ? 'border-red-300 text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500'
                        : 'border-gray-300 focus:ring-blue-500 focus:border-blue-500'
                    }
                    ${className}
                `}
                aria-invalid={error ? 'true' : 'false'}
                aria-describedby={error ? `${inputId}-error` : undefined}
                {...props}
            />
            {error && (
                <p
                    className="mt-1 text-sm text-red-600"
                    id={`${inputId}-error`}
                >
                    {error}
                </p>
            )}
        </div>
    );
});

Input.displayName = 'Input';

export default Input; 