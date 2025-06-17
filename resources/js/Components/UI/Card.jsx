import React from 'react';

const Card = ({ children, className = '' }) => {
    return (
        <div className={`bg-white overflow-hidden shadow-sm rounded-lg ${className}`}>
            {children}
        </div>
    );
};

const Header = ({ children, className = '' }) => {
    return (
        <div className={`px-4 py-5 sm:px-6 border-b border-gray-200 ${className}`}>
            {children}
        </div>
    );
};

const Body = ({ children, className = '' }) => {
    return (
        <div className={`px-4 py-5 sm:p-6 ${className}`}>
            {children}
        </div>
    );
};

const Footer = ({ children, className = '' }) => {
    return (
        <div className={`px-4 py-4 sm:px-6 border-t border-gray-200 ${className}`}>
            {children}
        </div>
    );
};

const Title = ({ children, className = '', as = 'h3' }) => {
    const Component = as;
    return (
        <Component className={`text-lg leading-6 font-medium text-gray-900 ${className}`}>
            {children}
        </Component>
    );
};

const Description = ({ children, className = '' }) => {
    return (
        <p className={`mt-1 max-w-2xl text-sm text-gray-500 ${className}`}>
            {children}
        </p>
    );
};

Card.Header = Header;
Card.Body = Body;
Card.Footer = Footer;
Card.Title = Title;
Card.Description = Description;

export default Card; 