                            <!-- Paso 5 -->
                            <div id="paso5" class="tab-pane" style="display: none;">

                                <legend>
                                    <h3>Datos del postulante</h3>
                                </legend>
                                <label>Los campos marcados con * son obligatorios </label>
                                <br>

                                <div class="control-group">
                                    <label class="control-label" for="nombreExperiencia">Nombre Experiencia *</label>
                                    <div class="controls">
                                        <input id="nombreExperiencia" name="nombreExperiencia" type="text"
                                            placeholder="Nombre de la Experiencia"
                                            title="Nombre de la Experienci. Máximo 300 caractres" class="input-xxlarge"
                                            maxlength="300">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <div class="controls">
                                        <label>Tiempo del Desarrollo de la Experiencia </label>
                                        <label class="radio inline">
                                            Años * &nbsp;&nbsp;&nbsp;
                                            <input id="annosExperiencia" name="annosExperiencia" type="text"
                                                placeholder="Años de Desarrollo de la Experiencia" class="input-large">
                                        </label>
                                        <label class="radio inline">
                                            Meses * &nbsp;&nbsp;&nbsp;
                                            <input id="mesesExperiencia" name="mesesExperiencia" type="text"
                                                placeholder="Meses de Desarrollo de la Experiencia" class="input-large">
                                        </label>
                                    </div>
                                </div>

                                <legend>Equipo de apoyo a la experiencia.</legend>
                                <div id="integrantes">
                                    <div class="control-group">
                                        <button type="button" class="btn btn-info insertparticipante">A&ntilde;adir
                                            Integrante</button>
                                    </div>
                                    <!-- Plantilla para agregar nuevos integrantes a instituciones -->
                                    <div class="form-group hidden" id="bookTemplate">
                                        <div style="border: 1px #000 solid ; margin-bottom: 10px; padding: 10px;">
                                            <div class="twoColumns">
                                                <div class="control-group">
                                                    <label class="control-label" for="idIntegrante">ID *</label>
                                                    <div class="controls">
                                                        <input id="idIntegrante" name="idIntegrante" type="text"
                                                            class="input-mini" disabled="true">
                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                    <label class="control-label" for="NIDparticipante">Documento
                                                        Identidad *</label>
                                                    <div class="controls">
                                                        <input id="NIDparticipante" name="NIDparticipante" type="text"
                                                            placeholder="Documento del Participante"
                                                            class="input-large">
                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                    <label class="control-label" for="nombreparticiapante">Nombres
                                                        Completos *</label>
                                                    <div class="controls">
                                                        <input id="nombreparticiapante" name="nombreparticiapante"
                                                            type="text" placeholder="Nombre Participante"
                                                            class="input-large">
                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                    <label class="control-label"
                                                        for="direccionparticiapante">Direcci&oacute;n *</label>
                                                    <div class="controls">
                                                        <input id="direccionparticiapante" name="direccionparticiapante"
                                                            type="text" placeholder="Direcci&oacute;n del  Participante"
                                                            class="input-large">
                                                    </div>
                                                </div>
                                                <br>
                                                <br>
                                                <div class="control-group">
                                                    <label class="control-label"
                                                        for="telefonoparticiapante">Tel&eacute;fono *</label>
                                                    <div class="controls">
                                                        <input id="telefonoparticiapante" name="telefonoparticiapante"
                                                            type="text" placeholder="Tel&eacute;fono del  Participante"
                                                            class="input-large">
                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                    <label class="control-label" for="emailparticipante">Correo
                                                        *</label>
                                                    <div class="controls">
                                                        <input id="emailparticipante" name="emailparticipante"
                                                            type="text" placeholder="E-mailparticipante"
                                                            class="input-large">
                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                    <label class="control-label" for="celparticipante">Celular *</label>
                                                    <div class="controls">
                                                        <input id="celparticipante" name="celparticipante" type="text"
                                                            placeholder="Celular del participante" class="input-large">
                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                    <label class="control-label" for="cargoparticipante">Cargo que ocupa
                                                        en la instituci&oacute;n *</label>
                                                    <div class="controls">
                                                        <input id="cargoparticipante" name="cargoparticipante"
                                                            type="text" placeholder="Cargo del participante"
                                                            class="input-large">
                                                    </div>
                                                </div>
                                            </div>
                                            <center>
                                                <div class="control-group">
                                                    <button type="button"
                                                        class="btn btn-warning removePariticipante">Eliminar
                                                        Integrante</button>
                                                </div>
                                            </center>
                                        </div>
                                    </div>
                                </div>

                                <legend></legend>

                                <div class="control-group">
                                    <label>Área (s) en la que se inscribe la experiencia </label>
                                    <br>
                                    <textarea id="areasExperiencia" name="areasExperiencia" class="input-block-level"
                                        rows="5"></textarea>
                                </div>

                                <div class="control-group">
                                    <label>En máximo 300 Palabras Describa qué Otras Áreas del Currículo se han
                                        Vinculado a la Experiencia y de qué Manera </label>
                                    <br>
                                    <textarea id="descripcionExperiencia" name="descripcionExperiencia"
                                        class="input-block-level" rows="5" maxlength="2000"></textarea>
                                </div>

                                <div class="control-group">
                                    <label>En Máximo 500 Palabras Escriba el Objetivo Principal de su Experiencia, que
                                        ha Logrado Mejorar en los Estudiantes del Establecimiento Educativo a Través del
                                        Ejercicio Pedagógico y Conceptual que se Desarrolla con Ella y Cuál es la
                                        Proyección Esperada con la Experiencia </label>
                                    <br>
                                    <textarea id="objetivoExperiencia" name="objetivoExperiencia"
                                        class="input-block-level" rows="5" maxlength="5000"></textarea>
                                </div>

                                <div class="control-group">
                                    <div class="controls">
                                        <label>Su Experiencia la ha Publicado en el Portal Educativo del MEN:
                                            Colombiaaprende *</label>
                                        <label class="radio inline" for="suExperienciaSePublico">
                                            <input type="radio" name="suExperienciaSePublico"
                                                id="suExperienciaSePublico-0" value="Si">
                                            Si
                                        </label>
                                        <label class="radio inline" for="suExperienciaSePublico">
                                            <input type="radio" name="suExperienciaSePublico"
                                                id="suExperienciaSePublico-1" value="No">
                                            No
                                        </label>
                                    </div>
                                </div>

                                <div id="divSiPublicoExperiencia" style="display: none;">


                                    <div class="control-group">
                                        <label class="control-label" for="nombreExperiencia">Nombre Experiencia
                                            Publicada *</label>
                                        <div class="controls">
                                            <input id="nombreExperienciaPublicada" name="nombreExperienciaPublicada"
                                                type="text"
                                                placeholder="Nombre con el Cual Está Publicada la Experiencia"
                                                title="Nombre con el Cual Está Publicada la Experiencia"
                                                class="input-xxlarge">
                                        </div>
                                    </div>

                                </div>
                                <div id="divNoPublicoExperiencia" style="display: none;">

                                    <div class="control-group">
                                        <div class="controls">
                                            <label>Estaría dispuesto (a) a hacer el ejercicio de publicación en el
                                                portal educativo del MEN: Colombiaaprende *</label>
                                            <label class="radio inline" for="siPublicarExperiencia">
                                                <input type="radio" name="siPublicarExperiencia"
                                                    id="siPublicarExperiencia-0" value="Si">
                                                Si
                                            </label>
                                            <label class="radio inline" for="siPublicarExperiencia">
                                                <input type="radio" name="siPublicarExperiencia"
                                                    id="siPublicarExperiencia-1" value="No">
                                                No
                                            </label>
                                        </div>
                                    </div>

                                </div>

                                <div class="control-group">
                                    <div class="controls">
                                        <label>Si la Experiencia la ha Publicado en Algún Portal Educativo Distinto al
                                            de Colombiaaprende, por Favor Escriba el Enlace de Internet </label>
                                        <label class="radio inline" for="suExperienciaSePublico">
                                            <input id="enlaceInternetExperiencia" name="enlaceInternetExperiencia"
                                                type="text"
                                                placeholder="Enlace de Internet donde se Encuentra Publicada la Experiencia "
                                                class="input-xxlarge">
                                        </label>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <div class="controls">
                                        <label>Ha Participado con la Experiencia que se está Postulando en Alguna
                                            Ponencia, Foro, Seminario u otro Similar del Sector Educativo *</label>
                                        <label class="radio inline" for="haParticipadoExperienciaOtroSector">
                                            <input type="radio" name="haParticipadoExperienciaOtroSector"
                                                id="haParticipadoExperienciaOtroSector-0" value="Si">
                                            Si
                                        </label>
                                        <label class="radio inline" for="suExperienciaSePublico">
                                            <input type="radio" name="haParticipadoExperienciaOtroSector"
                                                id="haParticipadoExperienciaOtroSector-1" value="No">
                                            No
                                        </label>
                                    </div>
                                </div>

                                <div id="divSiHaParticipadoExperienciaOtroSector" style="display: none;">

                                    <div class="control-group">
                                        <div class="controls">
                                            <div class="control-group">
                                                <label class="control-label" for="textinput">Nombre Evento *</label>
                                                <div class="controls">
                                                    <input id="nombreOtroEvento" name="nombreOtroEvento" type="text"
                                                        placeholder="Nombre del Evento" class="input-xlarge">
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label" for="textinput">Lugar Evento *</label>
                                                <div class="controls">
                                                    <input id="lugarOtroEvento" name="lugarOtroEvento" type="text"
                                                        placeholder="Lugar del Evento" class="input-xlarge">
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label" for="tipoDocuPartici">Tipo Evento *</label>
                                                <div class="controls">
                                                    <select id="tipoEvento" name="tipoEvento" class="input-large">
                                                        <option value="0">SELECCIONE TIPO DE EVENTO</option>
                                                        <option value="Local">Local</option>
                                                        <option value="Regional">Regional</option>
                                                        <option value="Nacional">Nacional</option>
                                                        <option value="Internacional">Internacional</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="control-group">
                                    <div class="controls">
                                        <label>Ha Sido Merecedor de Reconocimientos por esta Experiencia *</label>
                                        <label class="radio inline" for="haSidoMerecedorReconocimiento">
                                            <input type="radio" name="haSidoMerecedorReconocimiento"
                                                id="haSidoMerecedorReconocimiento-0" value="Si">
                                            Si
                                        </label>
                                        <label class="radio inline" for="haSidoMerecedorReconocimiento">
                                            <input type="radio" name="haSidoMerecedorReconocimiento"
                                                id="haSidoMerecedorReconocimiento-1" value="No">
                                            No
                                        </label>
                                    </div>
                                </div>

                                <div id="divHaSidoMerecedorReconocimiento" style="display: none;">

                                    <div class="control-group">
                                        <label class="control-label" for="textinput">Tipo Reconocimiento *</label>
                                        <div class="controls">
                                            <input id="tipoReconocimiento" name="tipoReconocimiento" type="text"
                                                placeholder="Tipo Reconocimiento" Reconocimiento class="input-xxlarge">
                                        </div>
                                    </div>

                                </div>

                                <br>
                                <center>
                                    <div class="control-group">
                                        <button id="singlebutton5" name="singlebutton5"
                                            class="btn btn-primary">Continuar</button>
                                        <button id="updateDocentes" name="updateDocentes" class="btn btn-primary"
                                            style="display: none;">Actualizar</button>
                                    </div>
                                </center>
                            </div>