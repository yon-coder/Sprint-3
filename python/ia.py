import json

def calcular_tmb(peso, altura, idade, sexo):
    if sexo == 'masculino':
        tmb = 88.36 + (13.4 * peso) + (4.8 * altura * 100) - (5.7 * idade)
    else:
        tmb = 447.6 + (9.2 * peso) + (3.1 * altura * 100) - (4.3 * idade)
    return tmb

def calcular_calorias(tmb, objetivo):
    if objetivo == 'ganhar':
        return int(tmb * 1.2)  # Calorias para ganho de massa
    elif objetivo == 'perder':
        return int(tmb * 0.8)  # Calorias para perda de peso
    else:
        return int(tmb * 1.0)  # Calorias para manutenção

def distribuir_macronutrientes(calorias, objetivo):
    if objetivo == 'ganhar':
        proteinas = 2.2  # g por kg
        carboidratos = 5  # g por kg
        gorduras = 1  # g por kg
    elif objetivo == 'perder':
        proteinas = 2.5
        carboidratos = 2.5
        gorduras = 0.8
    else:
        proteinas = 2.0
        carboidratos = 3.5
        gorduras = 1

    return {"proteinas": proteinas, "carboidratos": carboidratos, "gorduras": gorduras}

def gerar_treino(objetivo):
    if objetivo == 'ganhar':
        treino = "Treino de força 5x por semana com foco em hipertrofia."
    elif objetivo == 'perder':
        treino = "Treino HIIT e musculação 4x por semana."
    else:
        treino = "Treino funcional 3x por semana para manutenção."
    return treino

def gerar_dieta(peso, altura, idade, sexo, objetivo):
    tmb = calcular_tmb(peso, altura, idade, sexo)
    calorias = calcular_calorias(tmb, objetivo)
    macros = distribuir_macronutrientes(calorias, objetivo)

    return {
        "calorias_diarias": calorias,
        "proteinas": round(macros["proteinas"] * peso),
        "carboidratos": round(macros["carboidratos"] * peso),
        "gorduras": round(macros["gorduras"] * peso),
    }

# Dados de exemplo (poderiam vir do banco de dados)
# peso = 70
# altura = 1.75
# idade = 25
# sexo = 'masculino'
# objetivo = 'ganhar'

# dieta = gerar_dieta(peso, altura, idade, sexo, objetivo)
# treino = gerar_treino(objetivo)

# print(json.dumps({"dieta": dieta, "treino": treino}, indent=4))
