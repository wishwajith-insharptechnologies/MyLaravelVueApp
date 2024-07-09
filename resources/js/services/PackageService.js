import Http from "@/services/Http.js";


export const createPackage = async (formData) => {
    try {
        const response= await Http.post('package/create-package', formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
            },
        });
        return response.data;
    } catch (error) {
        throw error;
    }
};
