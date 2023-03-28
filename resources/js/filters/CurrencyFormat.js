export default function (value) {
    if (value) return (value).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
    else if (value == 0) return (0).toFixed(2);
}
