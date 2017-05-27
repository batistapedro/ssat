--
-- PostgreSQL database dump
--

SET statement_timeout = 0;
SET lock_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

--
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: usuarios; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE usuarios (
    id integer NOT NULL,
    nombre character(15),
    apellido character(15),
    usuario character(25),
    clave character(100),
    departamento_gerencia character(100),
    coordinacion character(70),
    cargo character(20),
    correo character(70),
    tipo_usuario integer,
    estado_usuario integer
);


ALTER TABLE usuarios OWNER TO postgres;

--
-- Name: usuarios_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE usuarios_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE usuarios_id_seq OWNER TO postgres;

--
-- Name: usuarios_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE usuarios_id_seq OWNED BY usuarios.id;


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY usuarios ALTER COLUMN id SET DEFAULT nextval('usuarios_id_seq'::regclass);


--
-- Data for Name: usuarios; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY usuarios (id, nombre, apellido, usuario, clave, departamento_gerencia, coordinacion, cargo, correo, tipo_usuario, estado_usuario) FROM stdin;
3	maria          	adolfo         	madolfo                  	adb29168563b9d4c006b4474577e4813                                                                    	atencion a la comunida                                                                              	n/a                                                                   	jefe                	mmadolfo@fondobolivar.gob.ve                                          	1	1
4	lisseth        	basantes       	lbasantes                	adb29168563b9d4c006b4474577e4813                                                                    	direccion ejecutiva                                                                                 	n/a                                                                   	secretaria          	lbasantes@fondobolivar.gob.ve                                         	1	1
1	pedro          	batista        	pbatista                 	adb29168563b9d4c006b4474577e4813                                                                    	telematica y sistema                                                                                	n/a                                                                   	analista            	pbatista@fondobolivar.gob.ve                                          	1	0
2	daniel         	almea          	dalmea                   	adb29168563b9d4c006b4474577e4813                                                                    	telematica y sistema                                                                                	n/a                                                                   	jefe                	dalmea@fondobolivar.gob.ve                                            	0	1
\.


--
-- Name: usuarios_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('usuarios_id_seq', 1, false);


--
-- Name: usuarios_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY usuarios
    ADD CONSTRAINT usuarios_pkey PRIMARY KEY (id);


--
-- Name: public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


--
-- PostgreSQL database dump complete
--

