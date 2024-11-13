<form action="#">
                <div class="mx-auto max-w-2xl px-4 lg:max-w-none lg:px-0">
                    <div>
                        <x-section-title title="Informaçao de contato" />

                        <div class="mt-6">
                            <x-input-label for="email-adress" value="Email"></x-input-label>
                            <div class="mt-1">
                                <x-text-input type="email" id="email-adress" name="email" autocomplete="email"
                                    placeholder="E-mail" required />
                            </div>
                        </div>
                        <div class="mt-10">
                            <x-section-title title="Detalhes do pagamento" />

                            <div class="mt-6 grid grid-col-3 gap-x-4 gap-y-6 sm:grid-cols-4">
                                <div class="col-span-3 sm:col-span-4">
                                    <x-input-label for="card-number" value="Numero do cartao"></x-input-label>
                                    <div class="mt-1">
                                        <x-text-input type="text" id="card-number" name="card-number"
                                            placeholder="Numero do cartao" autocomplete="cc-number" required />
                                    </div>
                                </div>
                                <div class="col-span-2 sm:col-span-3">
                                    <x-input-label for="expiration-date"
                                        value="Data de expiração (MM/YY)"></x-input-label>
                                    <div class="mt-1">
                                        <x-text-input type="text" id="expiration-date" name="expiration-date"
                                            placeholder="Numero do cartao" autocomplete="cc-number"
                                            autocomplete="cc-exp" required />
                                    </div>
                                </div>
                                <div>
                                    <x-input-label for="cvc" value="CVC"></x-input-label>
                                    <div class="mt-1">
                                        <x-text-input type="text" id="cvc" name="cvc"
                                            placeholder="Numero do cartao" autocomplete="csc" autocomplete="cc-exp"
                                            required />
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="mt-10">
                            <h3 class="text-lg font-medium text-white">Endereço</h3>

                            <div class="mt-6 grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-3">
                                <div class="sm:col-span-3">
                                    <label for="address" class="block text-sm font-medium text-white">Rua</label>
                                    <div class="mt-1">
                                        <input type="text" id="address" name="address"
                                            autocomplete="street-address"
                                            class="block w-full rounded-md text-primary-200 bg-tertiary-800 border-[#69727d] shadow-sm focus:border-primary-200 focus:ring-primary-200 sm:text-sm">
                                    </div>
                                </div>

                                <div>
                                    <label for="city" class="block text-sm font-medium text-white">Cidade</label>
                                    <div class="mt-1">
                                        <input type="text" id="city" name="city"
                                            autocomplete="address-level2"
                                            class="block w-full rounded-md text-primary-200 bg-tertiary-800 border-[#69727d] shadow-sm focus:border-primary-200 focus:ring-primary-200 sm:text-sm">
                                    </div>
                                </div>

                                <div>
                                    <label for="region" class="block text-sm font-medium text-white">Estado</label>
                                    <div class="mt-1">
                                        <input type="text" id="region" name="region"
                                            autocomplete="address-level1"
                                            class="block w-full rounded-md text-primary-200 bg-tertiary-800 border-[#69727d] shadow-sm focus:border-primary-200 focus:ring-primary-200 sm:text-sm">
                                    </div>
                                </div>

                                <div>
                                    <label for="postal-code" class="block text-sm font-medium text-white">CEP</label>
                                    <div class="mt-1">
                                        <input type="text" id="postal-code" name="postal-code"
                                            autocomplete="postal-code"
                                            class="block w-full rounded-md text-primary-200 bg-tertiary-800 border-[#69727d] shadow-sm focus:border-primary-200 focus:ring-primary-200 sm:text-sm">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-10 flex justify-end border-t border-tertiary-800-200 pt-6">
                            <button type="submit"
                                class="rounded-md border border-transparent bg-primary-200 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-tertiary-800-50">
                                Comprar
                            </button>
                        </div>
                    </div>
            </form>