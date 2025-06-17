import React from 'react';
import { Link } from '@inertiajs/react';

const Table = ({
    children,
    className = '',
    loading = false,
    empty = false,
    emptyMessage = 'No items found',
}) => {
    if (loading) {
        return (
            <div className="w-full">
                <div className="animate-pulse space-y-4">
                    <div className="h-4 bg-gray-200 rounded w-3/4"></div>
                    <div className="space-y-2">
                        {[...Array(3)].map((_, index) => (
                            <div key={index} className="h-4 bg-gray-200 rounded"></div>
                        ))}
                    </div>
                </div>
            </div>
        );
    }

    if (empty) {
        return (
            <div className="text-center py-12">
                <svg
                    className="mx-auto h-12 w-12 text-gray-400"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    aria-hidden="true"
                >
                    <path
                        strokeLinecap="round"
                        strokeLinejoin="round"
                        strokeWidth={2}
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"
                    />
                </svg>
                <h3 className="mt-2 text-sm font-medium text-gray-900">{emptyMessage}</h3>
            </div>
        );
    }

    return (
        <div className="overflow-x-auto shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
            <table className={`min-w-full divide-y divide-gray-300 ${className}`}>
                {children}
            </table>
        </div>
    );
};

const Head = ({ children, className = '' }) => {
    return (
        <thead className={`bg-gray-50 ${className}`}>
            {children}
        </thead>
    );
};

const Body = ({ children, className = '' }) => {
    return (
        <tbody className={`bg-white divide-y divide-gray-200 ${className}`}>
            {children}
        </tbody>
    );
};

const Row = ({ children, className = '', onClick, href, isEditing = false }) => {
    const classes = `${className} ${(onClick || href) ? 'cursor-pointer hover:bg-gray-50' : ''} ${isEditing ? 'bg-blue-50' : ''}`;

    if (href) {
        return (
            <Link href={href} className={classes}>
                <tr>{children}</tr>
            </Link>
        );
    }

    return (
        <tr className={classes} onClick={onClick}>
            {children}
        </tr>
    );
};

const Cell = ({ 
    children, 
    className = '', 
    header = false, 
    sortable = false, 
    sorted = null, 
    onSort,
    image = null,
    imageAlt = '',
    imageSize = 'small', // 'small', 'medium', 'large'
    actions = false
}) => {
    const Component = header ? 'th' : 'td';
    const baseClasses = header
        ? 'px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider'
        : 'px-6 py-4 whitespace-nowrap text-sm text-gray-900';

    const sortableClasses = sortable ? 'cursor-pointer group' : '';
    const sortedClasses = sorted === 'asc' ? 'text-blue-600' : sorted === 'desc' ? 'text-blue-600' : '';
    const actionClasses = actions ? 'w-px' : '';

    const imageSizeClasses = {
        small: 'h-8 w-8',
        medium: 'h-12 w-12',
        large: 'h-16 w-16'
    };

    return (
        <Component
            className={`${baseClasses} ${sortableClasses} ${sortedClasses} ${actionClasses} ${className}`}
            onClick={sortable ? onSort : undefined}
        >
            {image ? (
                <div className="flex items-center">
                    <div className="flex-shrink-0">
                        <img
                            src={image}
                            alt={imageAlt}
                            onError={(e) => {
                                e.target.src = `https://ui-avatars.com/api/?name=${encodeURIComponent(imageAlt)}&background=random`;
                            }}
                            className={`${imageSizeClasses[imageSize]} rounded-full object-cover`}
                        />
                    </div>
                    {children && (
                        <div className="ml-4">
                            {children}
                        </div>
                    )}
                </div>
            ) : (
                <div className="flex items-center space-x-1">
                    <span>{children}</span>
                    {sortable && (
                        <span className={`${sorted ? 'text-blue-600' : 'text-gray-400 group-hover:text-gray-500'}`}>
                            {sorted === 'asc' ? '↑' : sorted === 'desc' ? '↓' : '↕'}
                        </span>
                    )}
                </div>
            )}
        </Component>
    );
};

Table.Head = Head;
Table.Body = Body;
Table.Row = Row;
Table.Cell = Cell;

export default Table; 