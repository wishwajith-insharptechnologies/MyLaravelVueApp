import Http from "@/services/Http.js";

export function generateRandomString(length) {
    const characters =
        "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
    let result = "";
    for (let i = 0; i < length; i++) {
        result += characters.charAt(
            Math.floor(Math.random() * characters.length)
        );
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

export async function uploadFile(file) {
    const formData = new FormData();
    formData.append("file", file);

    try {
        const response = await Http.post("upload", formData, {
            headers: {
                "Content-Type": "multipart/form-data",
            },
        });

        return response;
    } catch (error) {
        throw error;
    }
}

export const deleteImage = async (fileId) => {
    try {
        const response = await Http.post("delete/file", formData, {
            headers: {
                "Content-Type": "multipart/form-data",
            },
        });
        return response.data;
    } catch (error) {
        throw error;
    }
};

export const getProjectTypes = async () => {
    try {
        const response = await Http.get('/get_project_types');
        return response.data.data;
    } catch (error) {
        console.error('Error fetching project types:', error);
        throw error;
    }
}

export  const getEnvironmentTypes = async () => {
    try {
        const response = await Http.get('/get_environment_types');
        return response.data.data;
    } catch (error) {
        console.error('Error fetching environment types:', error);
        throw error;
    }
}


