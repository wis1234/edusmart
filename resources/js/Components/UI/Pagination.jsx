import React from 'react';
import { Link } from '@inertiajs/react';

export default function Pagination({ links }) {
    // Don't render pagination if there's only one page
    if (links.length < 3) {
        return null;
    }

    return (
        <div className="flex items-center justify-between border-t border-gray-200 bg-white px-4 py-3 sm:px-6">
            <div className="flex flex-1 justify-between sm:hidden">
                {/* Mobile view */}
                {links[0].url && (
                    <Link
                        href={links[0].url}
                        className="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                    >
                        Previous
                    </Link>
                )}
                {links[links.length - 1].url && (
                    <Link
                        href={links[links.length - 1].url}
                        className="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                    >
                        Next
                    </Link>
                )}
            </div>
            <div className="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                <div>
                    <nav className="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                        {links.map((link, index) => {
                            // Skip the "Next" and "Previous" labels in the middle
                            if (link.label.includes('Previous') || link.label.includes('Next')) {
                                return null;
                            }

                            const isActive = link.active;

                            return (
                                <Link
                                    key={index}
                                    href={link.url ?? '#'}
                                    className={`relative inline-flex items-center px-4 py-2 text-sm font-semibold ${
                                        index === 0
                                            ? 'rounded-l-md'
                                            : index === links.length - 1
                                            ? 'rounded-r-md'
                                            : ''
                                    } ${
                                        isActive
                                            ? 'z-10 bg-blue-600 text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600'
                                            : link.url
                                            ? 'text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:outline-offset-0'
                                            : 'text-gray-300 ring-1 ring-inset ring-gray-300'
                                    }`}
                                    dangerouslySetInnerHTML={{ __html: link.label }}
                                />
                            );
                        })}
                    </nav>
                </div>
            </div>
        </div>
    );
} 