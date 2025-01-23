import { useEffect, useState } from 'react';
import { Head, useForm } from '@inertiajs/react';
import TextInput from '@/Components/TextInput';
import InputError from '@/Components/InputError';
import InputLabel from '@/Components/InputLabel';
import PrimaryButton from '@/Components/PrimaryButton';
import SelectInput from '@/Components/SelectInput';
import axios from 'axios';

export default function StaffRegister() {
    const [clinicName, setClinicName] = useState(null);
    const [isValidating, setIsValidating] = useState(false);

    const { data, setData, post, processing, errors, reset } = useForm({
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
        invitation_code: '',
        role: 'doctor'
    });

    const validateInvitationCode = async (code) => {
        if (!code) return;

        setIsValidating(true);
        try {
            const response = await axios.post(route('staff.validate-code'), {
                invitation_code: code
            });

            if (response.data.valid) {
                setClinicName(response.data.clinic.name);
            } else {
                setClinicName(null);
            }
        } catch (error) {
            setClinicName(null);
        } finally {
            setIsValidating(false);
        }
    };

    useEffect(() => {
        const timeoutId = setTimeout(() => {
            validateInvitationCode(data.invitation_code);
        }, 500);

        return () => clearTimeout(timeoutId);
    }, [data.invitation_code]);

    const submit = (e) => {
        e.preventDefault();
        post(route('staff.register'));
    };

    return (
        <>
            <Head title="Registro de Personal" />

            <div className="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
                <div className="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                    <h2 className="text-2xl font-bold mb-4 text-center">Registro de Personal</h2>

                    <form onSubmit={submit}>
                        {/* Código de Invitación */}
                        <div className="mt-4">
                            <InputLabel htmlFor="invitation_code" value="Código de Invitación" />
                            <TextInput
                                id="invitation_code"
                                type="text"
                                value={data.invitation_code}
                                onChange={(e) => setData('invitation_code', e.target.value)}
                                className="mt-1 block w-full"
                                autoComplete="off"
                            />
                            <InputError message={errors.invitation_code} className="mt-2" />
                            {clinicName && (
                                <div className="mt-2 text-sm text-green-600">
                                    Clínica encontrada: {clinicName}
                                </div>
                            )}
                        </div>

                        {/* Nombre */}
                        <div className="mt-4">
                            <InputLabel htmlFor="name" value="Nombre" />
                            <TextInput
                                id="name"
                                type="text"
                                value={data.name}
                                onChange={(e) => setData('name', e.target.value)}
                                className="mt-1 block w-full"
                                autoComplete="name"
                            />
                            <InputError message={errors.name} className="mt-2" />
                        </div>

                        {/* Email */}
                        <div className="mt-4">
                            <InputLabel htmlFor="email" value="Email" />
                            <TextInput
                                id="email"
                                type="email"
                                value={data.email}
                                onChange={(e) => setData('email', e.target.value)}
                                className="mt-1 block w-full"
                                autoComplete="username"
                            />
                            <InputError message={errors.email} className="mt-2" />
                        </div>

                        {/* Rol */}
                        <div className="mt-4">
                            <InputLabel htmlFor="role" value="Rol" />
                            <SelectInput
                                id="role"
                                value={data.role}
                                onChange={(e) => setData('role', e.target.value)}
                                className="mt-1 block w-full"
                            >
                                <option value="doctor">Doctor</option>
                                <option value="staff">Staff</option>
                            </SelectInput>
                            <InputError message={errors.role} className="mt-2" />
                        </div>

                        {/* Contraseña */}
                        <div className="mt-4">
                            <InputLabel htmlFor="password" value="Contraseña" />
                            <TextInput
                                id="password"
                                type="password"
                                value={data.password}
                                onChange={(e) => setData('password', e.target.value)}
                                className="mt-1 block w-full"
                                autoComplete="new-password"
                            />
                            <InputError message={errors.password} className="mt-2" />
                        </div>

                        {/* Confirmar Contraseña */}
                        <div className="mt-4">
                            <InputLabel htmlFor="password_confirmation" value="Confirmar Contraseña" />
                            <TextInput
                                id="password_confirmation"
                                type="password"
                                value={data.password_confirmation}
                                onChange={(e) => setData('password_confirmation', e.target.value)}
                                className="mt-1 block w-full"
                                autoComplete="new-password"
                            />
                            <InputError message={errors.password_confirmation} className="mt-2" />
                        </div>

                        <div className="flex items-center justify-end mt-4">
                            <PrimaryButton className="ml-4" disabled={processing || isValidating || !clinicName}>
                                Registrarse
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </>
    );
}
