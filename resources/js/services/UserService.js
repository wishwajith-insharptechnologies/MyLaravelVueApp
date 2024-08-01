import Http from "@/services/Http.js";

export const getAuthUser = async () => {
    try {
        const response = await Http.get("/user/auth");
        return response.data;
    } catch (error) {
        throw error;
    }
};

export const getAllUsers = async () => {
    try {
        const response = await Http.get("users");
        return response.data;
    } catch (error) {
        throw error;
    }
};

export const createUser = async (userData) => {
    try {
        const response = await Http.post("users/create-user", userData);
        return response.data;
    } catch (error) {
        throw error;
    }
};

export const updateUser = async (userData) => {
    try {
        const response = await Http.patch(
            `users/update-user/${userData.id}`,
            userData
        );
        return response.data;
    } catch (error) {
        throw error;
    }
};

export const deleteUser = async (id) => {
    try {
        const response = await Http.delete(`users/delete/user/${id}`);
        return response.data;
    } catch (error) {
        throw error;
    }
};

export const updatePassword = async (userData) => {
    try {
        const response = await Http.post("user/password-update", userData);
        return response.data;
    } catch (error) {
        throw error;
    }
};

export const isUserExists = async (email) => {
    try {
        const response = await Http.get("user-exists", email);
        return response.data;
    } catch (error) {
        throw error;
    }
};
