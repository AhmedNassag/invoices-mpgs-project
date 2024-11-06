export function validateAmount(amount) {
    // Try to parse the amount as a float
    const parsedAmount = parseFloat(amount);

    // Check if the parsed amount is a finite number and a positive number
    if (!isFinite(parsedAmount) || parsedAmount <= 0) {
        return 0.00;
    }

    // Regular expression to match a number with up to two decimal places
    const regex = /^\d+(\.\d{1,2})?$/;
    return regex.test(amount);
}
