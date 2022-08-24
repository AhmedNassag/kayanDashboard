export default (value) => {
    if (typeof value === "undefined" || value === null || value === "") {
        return true;
    }
    return /(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[$@$!%*?&])[A-Za-z\d$@$!%*?&].{8,}/.test(
        value
    );
};