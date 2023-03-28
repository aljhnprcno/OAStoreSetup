export default function (text, length) {
    if (text) {
        if (text.length > length) {
            return text.substring(0, length) + '...'
        } else {
            return text
        }
    } else {
        return ''
    }
}
