// export default function ApplicationLogo(props) {
//     return (
//         <img
//             {...props}
//             src="/logo.jped"
//             alt="EduSmart Logo"
//         />
//     );
// }


export default function ApplicationLogo(props) {
    return (
        <h1 {...props} className="text-3xl font-bold text-[#FF2D20] hover:text-[#e0261c] transition">
            Edu<span className="text-gray-900">Smart</span>
        </h1>
    );
}
