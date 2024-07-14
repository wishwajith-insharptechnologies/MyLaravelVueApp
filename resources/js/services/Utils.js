
export function generateRandomString(length) {
    const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    let result = '';
    for (let i = 0; i < length; i++) {
      result += characters.charAt(Math.floor(Math.random() * characters.length));
    }
    return result;
  }

  export function buildFormData(form) {
    const formData = new FormData();

    Object.entries(form).forEach(([key, value]) => {
        if (key === "limitation") {
            if (Array.isArray(value) && value.length > 0) {
                formData.append(key, JSON.stringify(value));
            }
        } else {

            formData.append(key, value);
        }
    });

    return formData;
}

