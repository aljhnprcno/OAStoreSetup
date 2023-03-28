export default function (text) {
    return text.split('_')
        .map(item => item.charAt(0).toUpperCase() + item.substring(1))
        .join(' ');
}
