const checkOverlap = (array1, array2) => {
    try {
        return array1.some(element => array2.includes(element));
    } catch (e) {
        console.error("Error checking for overlap, array1:", array1, "array2:", array2, "error:", e);
    }
}

const toCapitalCase = (str) => {
    return str.replace(/_/g, ' ').replace(/\b\w/g, char => char.toUpperCase());
}

const toSingular = (str) => {
    return str.replace(/s$/, '');
}

const formatDateForJS = (dateString) => {
    const date = new Date(dateString);
    date.setTime(date.getTime() + 8 * 60 * 60 * 1000); // Add 8 hours in milliseconds
    return date
}

export {checkOverlap, toCapitalCase, toSingular, formatDateForJS};